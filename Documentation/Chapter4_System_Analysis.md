# CHAPTER 4: SYSTEM ANALYSIS

## 4.1 Chapter Introduction

This chapter presents a comprehensive analysis of the current community support management system, including its strengths and weaknesses. The chapter includes a feasibility study to assess the viability of the proposed system, detailed requirements analysis identifying both functional and non-functional requirements, system analysis using Structured Systems Analysis and Design (SSAD) tools, identification of system users and their roles, entities and attributes, and database normalization. This analysis provides the foundation for the system design presented in the subsequent chapter.

## 4.2 Description of Current System

The current community support management system relies primarily on manual processes and paper-based record keeping. The system operates through community organizations, churches, and local groups that collect donations from members and distribute them to individuals in need within the community.

**Current Processes:**

1. **Donor Registration:** Donors register by providing their personal information including name, contact details, and preferred donation methods. Registration is typically done through paper forms filled out during community meetings or events.

2. **Donation Collection:** Donations are collected through various methods including cash contributions during meetings, mobile money transfers (M-Pesa), and bank transfers. Donation records are maintained in paper ledgers or basic spreadsheets.

3. **Victim Application:** Individuals seeking assistance submit applications through paper forms or verbal requests to community leaders. Applications include personal information, need description, and supporting documentation.

4. **Application Review:** Community leaders review victim applications based on established criteria including need level, community membership, and available resources. The review process is subjective and lacks standardized procedures.

5. **Fund Distribution:** Approved victims receive funds through cash handouts or mobile money transfers. Distribution records are maintained manually, often with incomplete information.

6. **Reporting:** Basic reports are generated periodically showing total donations received and funds distributed. These reports are often incomplete and lack detailed breakdowns or trend analysis.

**Strengths of Current System:**

- **Personal Touch:** The manual approach allows for personal interactions and community building among donors and recipients.
- **Flexibility:** The system can adapt to unique situations and special cases without rigid procedures.
- **Low Technical Barrier:** The system does not require technical skills or technology access, making it accessible to all community members.
- **Community Trust:** Established relationships and trust within the community facilitate the donation process.

**Weaknesses of Current System:**

- **Lack of Transparency:** Donors cannot track how their contributions are used or verify the impact of their donations, reducing donor confidence.
- **Inefficient Processes:** Manual processes are time-consuming and prone to errors, reducing overall efficiency.
- **Limited Record Keeping:** Paper-based records are difficult to maintain, search, and analyze, limiting the ability to track trends or make data-driven decisions.
- **Inconsistent Application Review:** The subjective nature of application review can lead to inconsistent decisions and potential bias.
- **Limited Accessibility:** The system relies on physical presence during meetings, limiting participation from individuals who cannot attend.
- **No Real-Time Reporting:** Lack of real-time data prevents timely decision-making and responsive resource allocation.
- **Security Concerns:** Cash handling and manual record keeping present security risks including theft, loss, and fraud.
- **Scalability Issues:** The manual approach does not scale well as the number of donors, victims, or transactions increases.

## 4.3 Feasibility Study

A comprehensive feasibility study was conducted to assess the viability of developing and implementing the NawiriKe Community Support Management System. The study examined technical, economic, operational, and schedule feasibility.

**Technical Feasibility:**

The proposed system uses well-established web technologies including PHP, MySQL, HTML5, CSS3, and JavaScript. These technologies are mature, widely supported, and have extensive documentation and community resources. The development team possesses the necessary technical skills to implement the system. The system requirements are modest and can be met by standard web hosting services. Integration with M-Pesa is technically feasible through available APIs. Therefore, the project is technically feasible.

**Economic Feasibility:**

The economic feasibility analysis considered both development costs and potential benefits. Development costs include time investment by the development team, web hosting expenses, and potential costs for M-Pesa API integration. These costs are relatively modest compared to the potential benefits including improved efficiency, increased donor participation, enhanced transparency, and better resource allocation. The system can reduce administrative overhead, increase donation volumes through improved donor confidence, and optimize resource allocation through data-driven decision making. The long-term benefits outweigh the initial investment, making the project economically feasible.

**Operational Feasibility:**

The proposed system is designed to be user-friendly and accessible to users with varying levels of technical proficiency. The system includes intuitive interfaces, clear instructions, and training materials to support user adoption. The transition from manual to digital processes will be supported through training sessions and ongoing technical support. The system is designed to work on standard devices including smartphones, tablets, and computers, ensuring accessibility for target users. Therefore, the project is operationally feasible.

**Schedule Feasibility:**

The project timeline was developed considering the complexity of the system, available resources, and academic requirements. The timeline includes adequate time for requirement specification, system analysis, design, implementation, testing, and documentation. The modular approach to development allows for incremental progress and flexibility to address unexpected challenges. The project can be completed within the allocated timeframe, making it schedule feasible.

**Feasibility Conclusion:**

The feasibility study concludes that the NawiriKe Community Support Management System is technically, economically, operationally, and schedule feasible. The project addresses significant weaknesses in the current system and has the potential to substantially improve community support management. The benefits of the system justify the investment of time and resources required for development and implementation.

## 4.4 Requirements Analysis

Requirements analysis involves identifying and documenting what the system should do (functional requirements) and how it should perform (non-functional requirements).

### 4.4.1 Functional Requirements

Functional requirements specify the functions and features that the system must provide.

**User Management Requirements:**

- FR1: The system shall allow users to register as donors, victims, or administrators.
- FR2: The system shall require users to provide personal information including name, email, and password during registration.
- FR3: The system shall allow donors to provide additional information including contact number for M-Pesa transactions.
- FR4: The system shall allow administrators to register using an admin verification code.
- FR5: The system shall authenticate users through login functionality using email and password.
- FR6: The system shall implement role-based access control to restrict users to functionality appropriate to their role.
- FR7: The system shall allow users to logout and terminate their sessions securely.

**Donation Management Requirements:**

- FR8: The system shall allow donors to make donations to specific victims.
- FR9: The system shall allow donors to contribute to a general pool fund.
- FR10: The system shall support multiple payment methods including cash and M-Pesa.
- FR11: The system shall record donation details including donor ID, victim ID (if applicable), amount, donation type, description, date, and payment method.
- FR12: The system shall allow donors to view their donation history.
- FR13: The system shall allow donors to track the status and impact of their donations.
- FR14: The system shall update donor total donated amount and donation count automatically upon successful donation.

**Victim Application Management Requirements:**

- FR15: The system shall allow individuals to submit victim applications including personal information and need description.
- FR16: The system shall allow administrators to view all submitted victim applications.
- FR17: The system shall allow administrators to approve victim applications based on establishedcriteria.
- FR18: The system shall allow administrators to reject victim applications with reasons.
- FR19: The system shall allow victims to view their application status.
- FR20: The system shall allow approved victims to receive distributions from the general pool.

**Fund Distribution Requirements:**

- FR21: The system shall allow administrators to view general pool statistics including total donations, total distributed, and available funds.
- FR22: The system shall allow administrators to distribute funds from the general pool to approved victims.
- FR23: The system shall record distribution details including victim ID, amount, date, and notes.
- FR24: The system shall update general pool statistics automatically upon distribution.
- FR25: The system shall prevent distributions that exceed available funds.

**Reporting Requirements:**

- FR26: The system shall provide administrators with comprehensive reports including total users, total donors, total victims, total donations, and total distributions.
- FR27: The system shall provide administrators with reports on approved and pending victim applications.
- FR28: The system shall provide administrators with reports on recent donations and distributions.
- FR29: The system shall provide administrators with reports on top donors.
- FR30: The system shall allow administrators to filter and sort reports by various criteria.
- FR31: The system shall allow administrators to export reports in various formats.

**Security Requirements:**

- FR32: The system shall hash user passwords using secure hashing algorithms.
- FR33: The system shall implement session management to maintain user authentication state.
- FR34: The system shall validate all user inputs to prevent security vulnerabilities.
- FR35: The system shall use prepared statements for database queries to prevent SQL injection.
- FR36: The system shall protect sensitive user information through appropriate access controls.

### 4.4.2 Non-Functional Requirements

Non-functional requirements specify how the system should perform and the quality attributes it must possess.

**Performance Requirements:**

- NFR1: The system shall respond to user requests within 3 seconds under normal load conditions.
- NFR2: The system shall support at least 100 concurrent users without significant performance degradation.
- NFR3: The system shall handle database queries efficiently to ensure timely data retrieval.

**Usability Requirements:**

- NFR4: The system shall provide intuitive and user-friendly interfaces.
- NFR5: The system shall be accessible to users with varying levels of technical proficiency.
- NFR6: The system shall provide clear instructions and error messages to guide users.
- NFR7: The system shall be responsive and function correctly on various devices including smartphones, tablets, and desktop computers.

**Security Requirements:**

- NFR8: The system shall protect user passwords using industry-standard hashing algorithms.
- NFR9: The system shall implement secure session management with appropriate timeout mechanisms.
- NFR10: The system shall protect against common web vulnerabilities including SQL injection, cross-site scripting, and cross-site request forgery.
- NFR11: The system shall implement role-based access control to ensure users can only access appropriate functionality.

**Reliability Requirements:**

- NFR12: The system shall maintain data integrity and consistency.
- NFR13: The system shall handle errors gracefully without crashing or data loss.
- NFR14: The system shall implement appropriate backup and recovery mechanisms.

**Scalability Requirements:**

- NFR15: The system shall be designed to accommodate growth in users, donations, and data volume.
- NFR16: The system architecture shall support future enhancements and feature additions.

**Maintainability Requirements:**

- NFR17: The system code shall be well-structured and documented to facilitate maintenance.
- NFR18: The system shall be designed with modular components to simplify updates and modifications.

## 4.5 System Analysis Using SSAD

Structured Systems Analysis and Design (SSAD) methodology is used to analyze the system by breaking it down into components and documenting data flows.

### 4.5.1 Context Diagram

The context diagram shows the system as a single process interacting with external entities. The NawiriKe CRM system interacts with the following external entities:

- **Donors:** Provide donations, view donation history, and track donation impact.
- **Victims:** Submit applications, view application status, and receive distributions.
- **Administrators:** Manage users, approve applications, distribute funds, and generate reports.
- **Payment System (M-Pesa):** Processes mobile money transactions for donations and distributions.

The context diagram illustrates the system boundaries and the high-level interactions between the system and external entities. Donors input donation information and receive confirmation and tracking information. Victims input application information and receive status updates and distribution notifications. Administrators input management decisions and receive comprehensive reports. The payment system processes transactions and provides confirmation to the system.

### 4.5.2 Data Flow Diagrams

**Level 1 Data Flow Diagram:**

The Level 1 DFD breaks down the system into major processes:

1. **User Management Process:** Handles user registration, authentication, and profile management.
2. **Donation Process:** Handles donation submission, processing, and tracking.
3. **Victim Application Process:** Handles application submission, review, and approval.
4. **Distribution Process:** Handles fund distribution from general pool to approved victims.
5. **Reporting Process:** Generates reports for administrators and donors.

Data flows between these processes and external entities show how information moves through the system. For example, donor information flows from donors to the User Management Process, donation information flows from donors to the Donation Process, and report information flows from the Reporting Process to administrators.

**Level 2 Data Flow Diagram - Donation Process:**

The Level 2 DFD for the donation process provides detailed breakdown:

1. **Validate Donor:** Verifies donor authentication and eligibility.
2. **Process Payment:** Interfaces with payment system to process donation.
3. **Record Donation:** Stores donation details in database.
4. **Update Statistics:** Updates donor statistics and general pool statistics.
5. **Send Confirmation:** Sends confirmation to donor.
6. **Track Donation:** Enables donor to track donation status and impact.

This detailed breakdown shows the specific steps involved in processing a donation and the data flows between each step.

## 4.6 System Users, Inputs and Outputs

**Table 4.3: System Users and Their Roles**

| User Role | Description | Primary Functions |
|-----------|-------------|-------------------|
| Administrator | System manager with full access | User management, application approval, fund distribution, report generation |
| Donor | Individual contributing donations | Registration, donation submission, donation tracking, history viewing |
| Victim | Individual seeking assistance | Application submission, status viewing, distribution tracking |

**System Inputs:**

- **User Registration Data:** Name, email, password, contact information, role selection.
- **Login Credentials:** Email, password.
- **Donation Data:** Amount, donation type (direct/general), recipient selection, payment method, description.
- **Victim Application Data:** Personal information, need description, supporting documentation.
- **Distribution Data:** Victim selection, amount, distribution notes.
- **Report Parameters:** Date ranges, user categories, data filters.

**System Outputs:**

- **User Confirmation Messages:** Registration success, login success, operation confirmations.
- **Error Messages:** Invalid input, authentication failures, operation errors.
- **Donation Receipts:** Transaction confirmation, donation details.
- **Application Status Updates:** Pending, approved, rejected notifications.
- **Distribution Notifications:** Distribution confirmation, amount received.
- **Reports:** User statistics, donation reports, distribution reports, financial summaries.
- **Dashboards:** Role-specific views with relevant information and actions.

## 4.7 Entities and Attributes

**Table 4.4: Entities and Attributes**

**Users Entity:**
- user_id (Primary Key)
- email
- password_hash
- role (admin/donor/victim)
- created_at
- updated_at

**Donors Entity:**
- donor_id (Primary Key)
- user_id (Foreign Key)
- firstname
- contact
- total_donated
- donation_count
- created_at
- updated_at

**Victims Entity:**
- victim_id (Primary Key)
- user_id (Foreign Key)
- firstname
- location
- need_description
- status (pending/approved/rejected)
- created_at
- updated_at

**Donations Entity:**
- donation_id (Primary Key)
- donor_id (Foreign Key)
- victim_id (Foreign Key, nullable)
- amount
- donation_type (direct/general)
- description
- donated_at
- status
- payment_method
- mpesa_phone

**Distributions Entity:**
- distribution_id (Primary Key)
- victim_id (Foreign Key)
- amount
- distributed_at
- notes

**General Pool Entity:**
- pool_id (Primary Key)
- total_pool
- total_distributed
- available
- distribution_count
- updated_at

## 4.8 Normalization

Database normalization is the process of organizing data to minimize redundancy and improve data integrity. The database for NawiriKe CRM has been normalized up to the Third Normal Form (3NF).

**First Normal Form (1NF):** All tables have a primary key, and all attributes contain atomic values. Each table represents a single entity, and repeating groups have been eliminated.

**Second Normal Form (2NF):** All non-key attributes are fully dependent on the entire primary key. Partial dependencies have been eliminated by creating separate tables for related entities.

**Third Normal Form (3NF):** All non-key attributes are directly dependent on the primary key, and transitive dependencies have been eliminated. For example, donor information is stored in a separate donors table rather than being repeated in the donations table.

The normalization process ensures data integrity, reduces redundancy, and facilitates efficient data management. The normalized database structure supports the system's requirements for data consistency, query performance, and maintainability.

## 4.9 Chapter Summary

This chapter presented a comprehensive analysis of the current community support management system, including its strengths and weaknesses. The feasibility study confirmed that the proposed system is technically, economically, operationally, and schedule feasible. Requirements analysis identified 36 functional requirements and 18 non-functional requirements that the system must satisfy.

System analysis using SSAD methodology included context diagrams and data flow diagrams to document system interactions and data flows. System users, inputs, and outputs were identified and documented. Database entities and attributes were defined, and the database was normalized up to the Third Normal Form to ensure data integrity and eliminate redundancy.

The analysis provides a solid foundation for system design, which will be presented in the next chapter. The design will translate the requirements and analysis into detailed specifications for the proposed system, including conceptual architecture, process design, database design, and user interface design.
