# CHAPTER 3: RESEARCH METHODOLOGY

## 3.1 Chapter Introduction

This chapter describes the research methodologies employed throughout the development of the NawiriKe Community Support Management System. The methodology encompasses requirement specification, system analysis, system design, implementation, testing, and deployment phases. Each phase is described in detail, including the tools, techniques, and approaches used to ensure the successful development of a robust, efficient, and user-friendly system.

## 3.2 Methodology of Requirement Specification

Requirement specification is a critical phase in system development that involves gathering, analyzing, and documenting the needs and expectations of system stakeholders. For this project, requirement specification was conducted using a combination of interviews, questionnaires, and document analysis.

**Interviews:** Semi-structured interviews were conducted with potential system users including community organization administrators, donors, and individuals who have previously sought assistance. The interviews aimed to understand current processes, identify pain points, and gather requirements for the proposed system. A total of 15 interviews were conducted, including 5 administrators, 7 donors, and 3 individuals who have sought assistance.

**Questionnaires:** Structured questionnaires were distributed to a broader sample of potential users to gather quantitative data on user preferences, feature requirements, and usability expectations. The questionnaires were distributed both online and in paper format, with 50 responses collected. The questionnaire covered areas such as preferred donation methods, desired reporting features, security concerns, and user interface preferences.

**Document Analysis:** Existing documentation from community organizations, including donation records, distribution logs, and operational procedures, were analyzed to understand current processes and identify areas for improvement. This analysis provided insights into data structures, workflow patterns, and reporting requirements.

The gathered requirements were categorized into functional requirements (what the system should do) and non-functional requirements (how the system should perform). Requirements were prioritized based on importance and feasibility, with core features such as user registration, donation processing, and reporting given highest priority.

## 3.3 Methodology for System Analysis

System analysis involves examining the current system to understand its operations, identify strengths and weaknesses, and determine requirements for the proposed system. For this project, system analysis was conducted using Structured Systems Analysis and Design (SSAD) methodology.

**Current System Description:** The current manual system for community support management was documented through observation and interviews with administrators. Key processes including donor registration, donation collection, victim application processing, and fund distribution were mapped to understand the current workflow.

**Context Diagram:** A context diagram was developed to show the system as a single process interacting with external entities including donors, victims, administrators, and payment systems. This diagram provided a high-level view of system boundaries and interactions.

**Data Flow Diagrams (DFD):** Level 1 and Level 2 DFDs were created to show the flow of data through the system. The Level 1 DFD showed the major processes within the system, while Level 2 DFDs provided detailed breakdowns of specific processes such as donation processing and victim application management.

**Normalization:** Database entities were identified and normalized up to the Third Normal Form (3NF) to ensure data integrity and eliminate redundancy. The normalization process involved identifying entities, attributes, and relationships, then systematically applying normalization rules to achieve an optimal database structure.

**Feasibility Study:** A feasibility study was conducted to assess the technical, economic, operational, and schedule feasibility of the proposed system. The study confirmed that the project is technically feasible using available web technologies, economically viable given the potential benefits, operationally practical for target users, and achievable within the project timeline.

## 3.4 Methodology for System Design

System design involves creating detailed specifications for the proposed system based on the requirements and analysis conducted. For this project, system design encompassed both process design and database design.

**Conceptual Architecture:** A three-tier architecture was adopted for the system, consisting of a presentation layer (user interface), application layer (business logic), and data layer (database). This architecture promotes separation of concerns, scalability, and maintainability.

**Process Design:** Process design was conducted using flowcharts to document the logic of key system processes including user registration, login, donation processing, victim application submission, and fund distribution. Flowcharts provided a visual representation of process steps, decision points, and system interactions.

**Database Design:** Database design involved creating an Entity Relationship Diagram (ERD) to show entities, attributes, and relationships. The ERD was used as the basis for creating database tables in MySQL. A data dictionary was developed to document each table, field, data type, and constraints.

**User Interface Design:** User interface design focused on creating intuitive, responsive, and visually appealing interfaces for each user role. Mock-up screens were created for key pages including landing page, login, registration, donor dashboard, victim dashboard, and admin dashboard. The design emphasized consistency, accessibility, and mobile responsiveness.

**Input/Output Design:** Input forms were designed with appropriate validation, error messages, and user guidance. Output reports were designed to present information clearly and concisely, with options for filtering, sorting, and exporting data.

## 3.5 Methodology for System Implementation

System implementation involves translating the design specifications into a working system using appropriate tools and technologies.

**Tool Selection:** The following tools were selected for implementation:

- **Programming Language:** PHP was selected for server-side scripting due to its widespread use, robust features, and compatibility with MySQL. PHP is particularly well-suited for web development and has extensive community support.

- **Database:** MySQL was selected as the database management system due to its reliability, performance, and open-source nature. MySQL integrates seamlessly with PHP and is widely used in web applications.

- **Front-end Technologies:** HTML5, CSS3, and JavaScript were selected for front-end development. These technologies provide the foundation for creating modern, responsive, and interactive user interfaces.

- **Development Environment:** XAMPP (Cross-Platform Apache, MySQL, PHP, Perl) was used as the local development environment, providing Apache web server, MySQL database, and PHP interpreter in a single package.

- **Version Control:** Git was used for version control to track changes, collaborate effectively, and maintain code integrity.

- **Code Editor:** Visual Studio Code was used as the primary code editor due to its features including syntax highlighting, code completion, and integrated debugging.

**Implementation Approach:** The system was implemented module by module, starting with core functionality including user authentication and database connectivity. Subsequent modules including donation processing, victim application management, and reporting were implemented incrementally. This modular approach facilitated testing and debugging.

**Security Implementation:** Security measures were implemented throughout the development process, including input validation, SQL injection prevention using prepared statements, password hashing using bcrypt, session management, and role-based access control.

## 3.6 Methodology for System Testing

System testing is conducted to ensure the system meets specified requirements and functions correctly in various scenarios.

**Testing Plan:** A comprehensive testing plan was developed covering functional testing, non-functional testing, integration testing, and user acceptance testing. The plan defined test cases, expected results, and acceptance criteria for each test.

**Testing Techniques:** Multiple testing techniques were employed:

- **Unit Testing:** Individual components and functions were tested in isolation to ensure they perform as expected. This included testing database connection functions, authentication functions, and data processing functions.

- **Integration Testing:** Integrated components were tested together to ensure they interact correctly. This included testing the interaction between user interface and database, and between different system modules.

- **Functional Testing:** System functionality was tested against the specified requirements. This included testing user registration, login, donation processing, victim application submission, and reporting features.

- **Non-Functional Testing:** System performance, usability, and security were tested. Performance testing assessed response times under various loads. Usability testing evaluated the ease of use and user satisfaction. Security testing identified vulnerabilities and confirmed the effectiveness of security measures.

- **User Acceptance Testing:** Potential users were invited to test the system and provide feedback. This testing ensured the system meets user needs and expectations.

**Bug Tracking and Resolution:** Identified issues were documented in a bug tracking system, prioritized based on severity, and systematically resolved. Regression testing was conducted after bug fixes to ensure new issues were not introduced.

## 3.7 Methodology for System Deployment

System deployment involves making the system available for use in the production environment.

**Deployment Strategy:** A phased deployment strategy was adopted, initially deploying the system to a limited user group for pilot testing before full deployment. This approach allowed for identification and resolution of issues before widespread rollout.

**Hosting Environment:** The system was deployed on a web hosting service with PHP and MySQL support. The hosting environment provided necessary security features, backup capabilities, and technical support.

**Data Migration:** Existing data from manual records were migrated to the new system. This involved data cleaning, formatting, and validation to ensure data quality. A data migration plan was developed and executed with minimal disruption to ongoing operations.

**User Training:** Training sessions were conducted for system users including administrators, donors, and victims. Training materials including user manuals and video tutorials were developed to support users in learning to use the system effectively.

**Support and Maintenance:** A support plan was established to provide ongoing technical support, address user issues, and implement system improvements. Regular maintenance activities including security updates, performance optimization, and feature enhancements were planned.

## 3.8 Chapter Summary

This chapter described the research methodologies employed throughout the development of the NawiriKe Community Support Management System. The methodology encompassed requirement specification using interviews and questionnaires, system analysis using SSAD methodology, system design including process and database design, implementation using PHP and MySQL, comprehensive testing, and systematic deployment.

The structured approach to each phase ensured that the system was developed systematically, with clear requirements, thorough analysis, detailed design, quality implementation, rigorous testing, and careful deployment. The methodologies selected are widely accepted in software engineering and provided a solid foundation for developing a robust, efficient, and user-friendly system.

The next chapter will present the system analysis, including a detailed description of the current system, feasibility study, requirements analysis, and system analysis using SSAD tools.
