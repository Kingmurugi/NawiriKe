# CHAPTER 5: SYSTEM DESIGN

## 5.1 Introduction

This chapter presents the detailed design of the proposed NawiriKe Community Support Management System. The design encompasses the conceptual architecture, process design using flowcharts, database design including Entity Relationship Diagrams (ERD) and data dictionary, and input/output design with mock-up screens. The design translates the requirements and analysis from previous chapters into detailed specifications that guide the implementation phase.

## 5.2 Description of Proposed System

The proposed NawiriKe CRM is a web-based Community Support Management System that addresses the weaknesses of the current manual system while leveraging technology to enhance efficiency, transparency, and accountability. The system provides a centralized platform for managing donations, victim applications, and fund distributions with real-time tracking and comprehensive reporting capabilities.

**Strengths of Proposed System:**

- **Enhanced Transparency:** Donors can track their donations in real-time and see how their contributions are being used, building confidence and encouraging continued participation.
- **Improved Efficiency:** Automated processes reduce manual effort, minimize errors, and accelerate donation processing and fund distribution.
- **Comprehensive Record Keeping:** Digital records enable easy searching, analysis, and reporting, supporting data-driven decision making.
- **Standardized Application Review:** Consistent criteria and documented processes ensure fair and objective victim application evaluation.
- **Increased Accessibility:** Web-based access allows users to participate from anywhere, at any time, using various devices.
- **Real-Time Reporting:** Up-to-date information enables timely decision-making and responsive resource allocation.
- **Enhanced Security:** Digital transactions and secure record keeping reduce security risks associated with cash handling and paper records.
- **Scalability:** The system can accommodate growth in users, donations, and data volume without proportional increases in administrative effort.

**Potential Weaknesses of Proposed System:**

- **Technical Barrier:** Users without internet access or technical skills may face challenges using the system.
- **Initial Learning Curve:** Users accustomed to manual processes may require time to adapt to the digital system.
- **Dependency on Technology:** System availability depends on internet connectivity and server reliability.
- **Implementation Costs:** Initial development and hosting costs represent an investment compared to minimal-cost manual processes.

## 5.3 Conceptual Architecture

The proposed system adopts a three-tier architecture, which is a well-established pattern for web applications that promotes separation of concerns, scalability, and maintainability.

**Presentation Layer (User Interface):**

The presentation layer consists of the user-facing interfaces that users interact with. This layer includes:
- Landing page with information about the platform
- Login and registration pages
- Donor dashboard for donation management
- Victim dashboard for application and distribution tracking
- Admin dashboard for system management and reporting
- Various forms for data input and pages for data display

The presentation layer is implemented using HTML5, CSS3, and JavaScript. HTML5 provides the structure, CSS3 handles styling and responsiveness, and JavaScript adds interactivity and client-side validation. The design emphasizes user experience with intuitive interfaces, clear navigation, and responsive design that works on various devices.

**Application Layer (Business Logic):**

The application layer contains the business logic that processes user requests, enforces business rules, and coordinates data access. This layer includes:
- User authentication and session management
- Donation processing logic
- Victim application processing logic
- Fund distribution logic
- Report generation logic
- Data validation and error handling

The application layer is implemented using PHP, a server-side scripting language well-suited for web development. PHP processes user requests, interacts with the database, applies business rules, and generates appropriate responses. The modular organization of PHP code facilitates maintenance and future enhancements.

**Data Layer (Database):**

The data layer manages data storage, retrieval, and manipulation. This layer includes:
- MySQL database management system
- Database tables for users, donors, victims, donations, distributions, and general pool
- Database queries for data access and manipulation
- Data integrity constraints and relationships

The data layer is implemented using MySQL, a robust relational database management system. MySQL provides reliable data storage, efficient querying, and transaction support. The database schema is normalized to ensure data integrity and eliminate redundancy.

**Architecture Benefits:**

The three-tier architecture provides several benefits:
- **Separation of Concerns:** Each layer has distinct responsibilities, making the system easier to understand, develop, and maintain.
- **Scalability:** Layers can be scaled independently based on demand. For example, the application layer can be scaled by adding more application servers without affecting the database layer.
- **Flexibility:** Changes to one layer can be made with minimal impact on other layers. For example, the user interface can be redesigned without changing the business logic or database.
- **Reusability:** Components within layers can be reused across different parts of the system.

## 5.4 Process Design

Process design documents the logic of key system processes using flowcharts. Flowcharts provide visual representations of process steps, decision points, and system interactions, facilitating understanding and implementation.

**Figure 5.2: Flowchart - User Registration Process**

The user registration process flowchart illustrates the following steps:

1. User accesses registration page
2. User enters personal information (name, email, password, role)
3. If role is donor, user enters contact information
4. If role is admin, user enters admin verification code
5. System validates input data
6. If validation fails, display error message and return to step 2
7. System checks if email already exists
8. If email exists, display error message and return to step 2
9. System hashes password
10. System stores user information in database
11. If role is donor, create donor record linked to user
12. If role is victim, create victim record linked to user
13. Display registration success message
14. Redirect to login page

**Figure 5.3: Flowchart - Donation Process**

The donation process flowchart illustrates the following steps:

1. Donor logs into system
2. Donor accesses donation page
3. Donor selects donation type (direct to victim or general pool)
4. If direct donation, donor selects victim from list
5. Donor enters donation amount
6. Donor selects payment method (cash or M-Pesa)
7. If M-Pesa, donor enters phone number
8. Donor enters description (optional)
9. System validates input data
10. If validation fails, display error message and return to step 3
11. System processes payment through selected method
12. If payment fails, display error message and return to step 3
13. System records donation in database
14. System updates donor total donated and donation count
15. If general pool donation, system updates general pool statistics
16. System sends confirmation to donor
17. Display donation success message
18. Redirect to donor dashboard

## 5.5 Database Design

Database design defines the structure, relationships, and constraints of the database that will store and manage system data.

### 5.5.1 Entity Relationship Diagram

**Figure 5.4: Entity Relationship Diagram**

The ERD for NawiriKe CRM includes the following entities and relationships:

**Users Entity:** Central entity representing all system users. Attributes include user_id (primary key), email, password_hash, role, created_at, and updated_at.

**Donors Entity:** Represents donor-specific information. Has a one-to-one relationship with Users entity. Attributes include donor_id (primary key), user_id (foreign key), firstname, contact, total_donated, donation_count, created_at, and updated_at.

**Victims Entity:** Represents victim-specific information. Has a one-to-one relationship with Users entity. Attributes include victim_id (primary key), user_id (foreign key), firstname, location, need_description, status, created_at, and updated_at.

**Donations Entity:** Represents donation transactions. Has a many-to-one relationship with Donors entity and a many-to-one relationship with Victims entity (optional). Attributes include donation_id (primary key), donor_id (foreign key), victim_id (foreign key, nullable), amount, donation_type, description, donated_at, status, payment_method, and mpesa_phone.

**Distributions Entity:** Represents fund distributions from general pool to victims. Has a many-to-one relationship with Victims entity. Attributes include distribution_id (primary key), victim_id (foreign key), amount, distributed_at, and notes.

**General Pool Entity:** Represents aggregated statistics for the general fund. Attributes include pool_id (primary key), total_pool, total_distributed, available, distribution_count, and updated_at.

Relationships:
- Each User can be associated with at most one Donor record (one-to-one)
- Each User can be associated with at most one Victim record (one-to-one)
- Each Donor can make multiple Donations (one-to-many)
- Each Donation can be associated with at most one Victim (many-to-one)
- Each Victim can receive multiple Distributions (one-to-many)
- Each Victim can be associated with multiple Donations (one-to-many)

### 5.5.2 Data Dictionary

**Table 5.1: Data Dictionary - Users Table**

| Field Name | Data Type | Size | Constraints | Description |
|------------|-----------|------|-------------|-------------|
| user_id | INT | - | Primary Key, Auto Increment | Unique identifier for each user |
| email | VARCHAR | 255 | Unique, Not Null | User's email address for login |
| password_hash | VARCHAR | 255 | Not Null | Hashed password for authentication |
| role | ENUM | - | Not Null | User role: 'admin', 'donor', 'victim' |
| created_at | TIMESTAMP | - | Default Current Timestamp | Record creation timestamp |
| updated_at | TIMESTAMP | - | Default Current Timestamp on Update | Record last update timestamp |

**Table 5.2: Data Dictionary - Donors Table**

| Field Name | Data Type | Size | Constraints | Description |
|------------|-----------|------|-------------|-------------|
| donor_id | INT | - | Primary Key, Auto Increment | Unique identifier for each donor |
| user_id | INT | - | Foreign Key, Not Null | Reference to Users table |
| firstname | VARCHAR | 100 | Not Null | Donor's first name |
| contact | VARCHAR | 20 | Not Null | Contact number for M-Pesa |
| total_donated | DECIMAL | 10,2 | Default 0.00 | Total amount donated by donor |
| donation_count | INT | - | Default 0 | Number of donations made by donor |
| created_at | TIMESTAMP | - | Default Current Timestamp | Record creation timestamp |
| updated_at | TIMESTAMP | - | Default Current Timestamp on Update | Record last update timestamp |

**Table 5.3: Data Dictionary - Victims Table**

| Field Name | Data Type | Size | Constraints | Description |
|------------|-----------|------|-------------|-------------|
| victim_id | INT | - | Primary Key, Auto Increment | Unique identifier for each victim |
| user_id | INT | - | Foreign Key, Not Null | Reference to Users table |
| firstname | VARCHAR | 100 | Not Null | Victim's first name |
| location | VARCHAR | 255 | Not Null | Victim's location |
| need_description | TEXT | - | Not Null | Description of victim's needs |
| status | ENUM | - | Default 'pending' | Application status: 'pending', 'approved', 'rejected' |
| created_at | TIMESTAMP | - | Default Current Timestamp | Record creation timestamp |
| updated_at | TIMESTAMP | - | Default Current Timestamp on Update | Record last update timestamp |

**Table 5.4: Data Dictionary - Donations Table**

| Field Name | Data Type | Size | Constraints | Description |
|------------|-----------|------|-------------|-------------|
| donation_id | INT | - | Primary Key, Auto Increment | Unique identifier for each donation |
| donor_id | INT | - | Foreign Key, Not Null | Reference to Donors table |
| victim_id | INT | - | Foreign Key, Nullable | Reference to Victims table (null for general pool) |
| amount | DECIMAL | 10,2 | Not Null | Donation amount |
| donation_type | ENUM | - | Not Null | Type: 'direct', 'general' |
| description | TEXT | - | Nullable | Donation description |
| donated_at | TIMESTAMP | - | Default Current Timestamp | Donation timestamp |
| status | ENUM | - | Default 'completed' | Donation status: 'pending', 'completed', 'failed' |
| payment_method | ENUM | - | Not Null | Payment method: 'cash', 'mpesa' |
| mpesa_phone | VARCHAR | 20 | Nullable | M-Pesa phone number |

**Table 5.5: Data Dictionary - Distributions Table**

| Field Name | Data Type | Size | Constraints | Description |
|------------|-----------|------|-------------|-------------|
| distribution_id | INT | - | Primary Key, Auto Increment | Unique identifier for each distribution |
| victim_id | INT | - | Foreign Key, Not Null | Reference to Victims table |
| amount | DECIMAL | 10,2 | Not Null | Distribution amount |
| distributed_at | TIMESTAMP | - | Default Current Timestamp | Distribution timestamp |
| notes | TEXT | - | Nullable | Distribution notes |

## 5.6 Input/Output Design

Input/output design defines the interfaces through which users interact with the system and the formats in which information is presented.

**Input Design:**

Input forms are designed with the following principles:
- Clear labeling of all fields
- Appropriate input types for different data (email, password, number, text)
- Client-side validation for immediate feedback
- Server-side validation for security and data integrity
- Helpful error messages to guide users
- Consistent styling across all forms

Key input forms include:
- Registration form with role-specific fields
- Login form with email and password fields
- Donation form with amount, recipient, and payment method fields
- Victim application form with personal information and need description
- Distribution form with victim selection and amount fields

**Output Design:**

Output is designed to present information clearly and concisely:
- Well-organized dashboards with relevant information for each user role
- Tables with sortable columns for data lists
- Summary cards showing key statistics
- Charts and graphs for visual representation of data
- Exportable reports in various formats (PDF, Excel)
- Responsive design that works on various devices

Key output interfaces include:
- Donor dashboard showing donation history and statistics
- Victim dashboard showing application status and received distributions
- Admin dashboard with comprehensive system statistics and management tools
- Reports showing user statistics, donation trends, and distribution history

## 5.7 Test Data

Test data represents sample data that will be used to test the system functionality. Adequate test data should represent actual input scenarios in terms of variance to ensure the system handles various situations correctly.

**User Test Data:**
- Multiple admin users with different permissions
- Donors with varying donation histories and patterns
- Victims at different application stages (pending, approved, rejected)
- Users with edge cases (special characters in names, very long descriptions, etc.)

**Donation Test Data:**
- Donations of various amounts (small, medium, large)
- Direct donations to different victims
- General pool donations
- Donations through different payment methods (cash, M-Pesa)
- Donations with various descriptions
- Failed donation scenarios

**Victim Application Test Data:**
- Applications with various need descriptions
- Applications at different stages of approval
- Edge cases (very long descriptions, special characters, etc.)

**Distribution Test Data:**
- Distributions of various amounts
- Distributions to different victims
- Distributions with various notes
- Edge cases (distributions exceeding available funds, etc.)

## 5.8 Chapter Summary

This chapter presented the detailed design of the proposed NawiriKe Community Support Management System. The design included a description of the proposed system highlighting its strengths and potential weaknesses compared to the current manual system. A three-tier conceptual architecture was adopted, consisting of presentation, application, and data layers, promoting separation of concerns and scalability.

Process design was documented using flowcharts for key processes including user registration and donation processing. Database design included an Entity Relationship Diagram showing entities, attributes, and relationships, along with a comprehensive data dictionary documenting each table, field, data type, and constraints.

Input/output design principles were established, emphasizing clear labeling, validation, and user-friendly interfaces. Test data requirements were identified to ensure comprehensive testing of system functionality.

The design provides detailed specifications that guide the implementation phase, ensuring the system meets the requirements identified in previous chapters and addresses the weaknesses of the current manual system. The next chapter will describe the implementation of the system, including user interface implementation, database implementation, process design implementation, and module testing.
