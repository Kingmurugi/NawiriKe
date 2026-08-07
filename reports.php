<?php
/**
 * NawiriKe CRM Reports
 * Report queries used by the admin dashboard and the CSV exporter.
 *
 * Every report returns a uniform structure so the dashboard and the exporter can
 * render any report without knowing its columns:
 *   ['title' => string, 'columns' => ['key' => 'Label', ...], 'rows' => [[...], ...]]
 *
 * Monetary figures only count donations with status = 'completed', so pending
 * M-Pesa pushes are never reported as money received.
 */

/**
 * All reports available in the admin panel, keyed by their URL slug
 * @return array<string, string> slug => human readable title
 */
function getAvailableReports() {
    return [
        'donations'    => 'Donations Report',
        'donors'       => 'Donors Report',
        'victims'      => 'Victims / Applications Report',
        'benefits'     => 'Benefits Distributed Report',
        'monthly'      => 'Monthly Summary Report',
    ];
}

/**
 * Build a single report by slug
 * @return array|null Report structure, or null when the slug is unknown
 */
function getReport($conn, $slug, $limit = null) {
    switch ($slug) {
        case 'donations':
            return getDonationsReport($conn, $limit);
        case 'donors':
            return getDonorsReport($conn, $limit);
        case 'victims':
            return getVictimsReport($conn, $limit);
        case 'benefits':
            return getBenefitsReport($conn, $limit);
        case 'monthly':
            return getMonthlySummaryReport($conn, $limit);
        default:
            return null;
    }
}

/**
 * Append a LIMIT clause when a row cap is requested
 */
function reportLimitClause($limit) {
    return ($limit === null) ? '' : ' LIMIT ' . (int)$limit;
}

/**
 * Every donation in the system, newest first
 */
function getDonationsReport($conn, $limit = null) {
    $stmt = $conn->prepare("
        SELECT d.donation_id,
               COALESCE(du.name, 'Anonymous') AS donor_name,
               COALESCE(vu.name, 'General Pool') AS beneficiary,
               CASE WHEN d.victim_id IS NULL THEN 'General Pool' ELSE 'Direct' END AS channel,
               d.amount,
               d.donation_type,
               d.payment_method,
               d.status,
               d.donated_at
        FROM donations d
        LEFT JOIN donors dn ON d.donor_id = dn.donor_id
        LEFT JOIN users du ON dn.user_id = du.user_id
        LEFT JOIN victims v ON d.victim_id = v.victim_id
        LEFT JOIN users vu ON v.user_id = vu.user_id
        ORDER BY d.donated_at DESC, d.donation_id DESC
    " . reportLimitClause($limit));
    $stmt->execute();

    return [
        'title' => 'Donations Report',
        'columns' => [
            'donation_id' => 'ID',
            'donor_name' => 'Donor',
            'beneficiary' => 'Beneficiary',
            'channel' => 'Channel',
            'amount' => 'Amount (KES)',
            'donation_type' => 'Type',
            'payment_method' => 'Method',
            'status' => 'Status',
            'donated_at' => 'Date',
        ],
        'rows' => $stmt->fetchAll(),
    ];
}

/**
 * Donor contributions, ranked by amount given
 * Totals are recomputed from the donations table rather than read from
 * donors.total_donated, so the report stays correct even if that cached column drifts.
 */
function getDonorsReport($conn, $limit = null) {
    $stmt = $conn->prepare("
        SELECT dn.donor_id,
               u.name AS donor_name,
               u.email,
               dn.contact,
               COALESCE(SUM(CASE WHEN d.status = 'completed' THEN d.amount END), 0) AS total_donated,
               COUNT(CASE WHEN d.status = 'completed' THEN 1 END) AS donations_made,
               COUNT(DISTINCT d.victim_id) AS victims_helped,
               MAX(d.donated_at) AS last_donation
        FROM donors dn
        JOIN users u ON dn.user_id = u.user_id
        LEFT JOIN donations d ON d.donor_id = dn.donor_id
        GROUP BY dn.donor_id, u.name, u.email, dn.contact
        ORDER BY total_donated DESC, u.name ASC
    " . reportLimitClause($limit));
    $stmt->execute();

    return [
        'title' => 'Donors Report',
        'columns' => [
            'donor_id' => 'ID',
            'donor_name' => 'Donor',
            'email' => 'Email',
            'contact' => 'Contact',
            'total_donated' => 'Total Donated (KES)',
            'donations_made' => 'Donations',
            'victims_helped' => 'Victims Helped',
            'last_donation' => 'Last Donation',
        ],
        'rows' => $stmt->fetchAll(),
    ];
}

/**
 * Victim applications with the support each one has received
 * Support = direct donations + benefits distributed from the general pool.
 */
function getVictimsReport($conn, $limit = null) {
    $stmt = $conn->prepare("
        SELECT v.victim_id,
               u.name AS victim_name,
               v.location,
               v.urgent_needs,
               v.verification_status,
               v.date_registered,
               COALESCE(direct.total, 0) AS direct_received,
               COALESCE(benefits.total, 0) AS benefits_received,
               COALESCE(direct.total, 0) + COALESCE(benefits.total, 0) AS total_received
        FROM victims v
        JOIN users u ON v.user_id = u.user_id
        LEFT JOIN (
            SELECT victim_id, SUM(amount) AS total
            FROM donations
            WHERE victim_id IS NOT NULL AND status = 'completed'
            GROUP BY victim_id
        ) direct ON direct.victim_id = v.victim_id
        LEFT JOIN (
            SELECT victim_id, SUM(amount) AS total
            FROM distributions
            GROUP BY victim_id
        ) benefits ON benefits.victim_id = v.victim_id
        ORDER BY total_received DESC, v.date_registered DESC
    " . reportLimitClause($limit));
    $stmt->execute();

    return [
        'title' => 'Victims / Applications Report',
        'columns' => [
            'victim_id' => 'ID',
            'victim_name' => 'Victim',
            'location' => 'Location',
            'urgent_needs' => 'Urgent Need',
            'verification_status' => 'Status',
            'date_registered' => 'Registered',
            'direct_received' => 'Direct (KES)',
            'benefits_received' => 'Benefits (KES)',
            'total_received' => 'Total (KES)',
        ],
        'rows' => $stmt->fetchAll(),
    ];
}

/**
 * Benefits paid out of the general pool by admins
 */
function getBenefitsReport($conn, $limit = null) {
    $stmt = $conn->prepare("
        SELECT ds.distribution_id,
               vu.name AS victim_name,
               v.location,
               v.urgent_needs,
               ds.amount,
               ds.donation_id AS source_donation,
               au.name AS distributed_by,
               ds.distribution_date,
               ds.notes
        FROM distributions ds
        JOIN victims v ON ds.victim_id = v.victim_id
        JOIN users vu ON v.user_id = vu.user_id
        JOIN users au ON ds.distributed_by = au.user_id
        ORDER BY ds.distribution_date DESC, ds.distribution_id DESC
    " . reportLimitClause($limit));
    $stmt->execute();

    return [
        'title' => 'Benefits Distributed Report',
        'columns' => [
            'distribution_id' => 'ID',
            'victim_name' => 'Beneficiary',
            'location' => 'Location',
            'urgent_needs' => 'Need Addressed',
            'amount' => 'Amount (KES)',
            'source_donation' => 'Source Donation',
            'distributed_by' => 'Distributed By',
            'distribution_date' => 'Date',
            'notes' => 'Notes',
        ],
        'rows' => $stmt->fetchAll(),
    ];
}

/**
 * Month-by-month donation and benefit activity
 */
function getMonthlySummaryReport($conn, $limit = null) {
    $stmt = $conn->prepare("
        SELECT period,
               SUM(donations_count) AS donations_count,
               SUM(donations_total) AS donations_total,
               SUM(general_pool_total) AS general_pool_total,
               SUM(benefits_count) AS benefits_count,
               SUM(benefits_total) AS benefits_total
        FROM (
            SELECT DATE_FORMAT(donated_at, '%Y-%m') AS period,
                   COUNT(*) AS donations_count,
                   SUM(amount) AS donations_total,
                   SUM(CASE WHEN victim_id IS NULL THEN amount ELSE 0 END) AS general_pool_total,
                   0 AS benefits_count,
                   0 AS benefits_total
            FROM donations
            WHERE status = 'completed'
            GROUP BY period
            UNION ALL
            SELECT DATE_FORMAT(distribution_date, '%Y-%m') AS period,
                   0, 0, 0,
                   COUNT(*) AS benefits_count,
                   SUM(amount) AS benefits_total
            FROM distributions
            GROUP BY period
        ) activity
        GROUP BY period
        ORDER BY period DESC
    " . reportLimitClause($limit));
    $stmt->execute();

    return [
        'title' => 'Monthly Summary Report',
        'columns' => [
            'period' => 'Month',
            'donations_count' => 'Donations',
            'donations_total' => 'Donated (KES)',
            'general_pool_total' => 'To General Pool (KES)',
            'benefits_count' => 'Benefits',
            'benefits_total' => 'Benefits Paid (KES)',
        ],
        'rows' => $stmt->fetchAll(),
    ];
}

/**
 * Present a report value for display: amounts get thousands separators,
 * timestamps become readable dates, enum values are capitalised.
 */
function formatReportCell($key, $value) {
    if ($value === null || $value === '') {
        return '-';
    }

    if (preg_match('/amount|total|donated|received/', $key)) {
        return number_format((float)$value, 2);
    }

    if (preg_match('/_at$|_date$|date_|last_donation/', $key)) {
        $timestamp = strtotime((string)$value);
        return $timestamp ? date('M j, Y', $timestamp) : (string)$value;
    }

    if (in_array($key, ['status', 'donation_type', 'payment_method', 'urgent_needs', 'channel'], true)) {
        return ucfirst((string)$value);
    }

    return (string)$value;
}

/**
 * Headline figures for the admin dashboard cards
 */
function getAdminDashboardStats($conn) {
    $stmt = $conn->prepare("
        SELECT
            (SELECT COUNT(*) FROM users) AS total_users,
            (SELECT COUNT(*) FROM donors) AS total_donors,
            (SELECT COUNT(*) FROM victims) AS total_victims,
            (SELECT COALESCE(SUM(amount), 0) FROM donations WHERE status = 'completed') AS total_donations,
            (SELECT COUNT(*) FROM donations WHERE status = 'completed') AS donation_count,
            (SELECT COALESCE(SUM(amount), 0) FROM donations WHERE status = 'pending') AS pending_donations
    ");
    $stmt->execute();

    return $stmt->fetch();
}
