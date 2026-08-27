# CHAPTER 1: INTRODUCTION

## 1.1 Background of the Study

Community support and humanitarian aid have always been fundamental aspects of human society, particularly in developing countries where social safety nets may be limited or non-existent. In Kenya, like many other developing nations, there exists a significant gap between individuals willing to donate resources and those in need of assistance. This disconnect often results in inefficient resource allocation, lack of transparency in donation management, and reduced impact of charitable efforts.

The traditional approach to community support in Kenya has largely relied on manual processes, paper-based record keeping, and informal networks. These methods are characterized by numerous inefficiencies including difficulty in tracking donations, lack of real-time reporting, challenges in verifying recipient needs, and limited transparency in fund distribution. Such inefficiencies not only reduce the effectiveness of community support initiatives but also erode donor confidence, potentially reducing the overall participation in charitable activities.

With the advent of digital technologies and the widespread adoption of mobile money services such as M-Pesa in Kenya, there exists an opportunity to revolutionize how community support is managed and delivered. Technology can bridge the gap between donors and recipients, provide transparency in donation management, enable real-time tracking of resources, and ultimately enhance the impact of community support initiatives.

Customer Relationship Management (CRM) systems have been successfully implemented in various sectors to manage interactions with customers, streamline processes, and improve overall efficiency. Adapting CRM principles to community support management presents a novel approach to addressing the challenges faced by traditional charitable organizations. A Community Support Management System can provide a centralized platform for managing donations, tracking distributions, and ensuring accountability throughout the entire process.

The NawiriKe CRM project aims to leverage web-based technologies and mobile money integration to create an efficient, transparent, and user-friendly platform for managing community support initiatives. By digitizing the donation management process, the system seeks to enhance transparency, improve efficiency, and increase the overall impact of community support efforts in Kenya.

## 1.2 Problem Statement

The current manual processes for managing community support initiatives in Kenya are characterized by significant inefficiencies that hinder the effective delivery of aid to those in need. These challenges include lack of transparency in donation management, difficulties in tracking donations from donors to recipients, inefficient resource allocation, and limited real-time reporting capabilities. The absence of a centralized system for managing community support initiatives results in reduced donor confidence, as donors cannot easily track how their contributions are being used or verify the impact of their donations.

Furthermore, the manual nature of current processes makes it difficult to maintain accurate records of donations, distributions, and recipient information. This lack of comprehensive data makes it challenging to analyze trends, measure impact, or make data-driven decisions to improve community support initiatives. The verification process for individuals seeking assistance is often cumbersome and inconsistent, leading to potential delays in aid delivery and possible misallocation of resources.

The integration of mobile money services, which are widely used in Kenya, is often lacking in current community support management approaches. This disconnect limits the accessibility and convenience of donation processes, potentially reducing participation from donors who prefer digital payment methods. Additionally, the absence of role-based access controls and comprehensive reporting capabilities makes it difficult for administrators to effectively manage and monitor community support initiatives.

These challenges collectively contribute to reduced efficiency, transparency, and accountability in community support management, ultimately limiting the positive impact that charitable efforts can have on communities in need.

## 1.3 Main Objective

The main objective of this project is to design and implement a web-based Community Support Management System (NawiriKe CRM) that streamlines the process of donation management, victim registration, and fund distribution to enhance transparency, efficiency, and accountability in community support initiatives.

## 1.4 Specific Objectives

1. To analyze the current manual processes for community support management and identify key inefficiencies and areas for improvement.

2. To design a centralized database system that securely stores and manages donor, victim, donation, and distribution information.

3. To develop user interfaces for different user roles (donors, victims, administrators) that provide role-specific functionality and enhance user experience.

4. To implement secure donation processing capabilities with integration to mobile money services (M-Pesa) for convenient and accessible donation methods.

5. To develop real-time tracking and reporting features that enable donors to monitor their donations and administrators to generate comprehensive reports on community support activities.

6. To implement role-based access control and security measures to protect sensitive user information and ensure system integrity.

7. To test the system thoroughly to ensure it meets the specified requirements and functions correctly in real-world scenarios.

## 1.5 Justification

The development of NawiriKe CRM is justified by the need to address the significant inefficiencies present in current community support management approaches. Research by Smith and Johnson (2022) indicates that digital transformation in charitable organizations can increase donation efficiency by up to 40% while improving donor confidence through enhanced transparency. The implementation of a centralized management system aligns with global trends toward digital solutions in the humanitarian sector, as evidenced by the success of platforms such as GoFundMe and DonorsChoose in developed countries.

The integration of mobile money services is particularly relevant in the Kenyan context, where M-Pesa has achieved over 90% adoption rate among adults (Central Bank of Kenya, 2023). Leveraging this existing infrastructure can significantly enhance the accessibility and convenience of donation processes, potentially increasing participation rates among donors who prefer digital payment methods.

From a technical perspective, the adoption of web-based technologies and database management systems for community support management is supported by numerous studies demonstrating the benefits of digitization in improving operational efficiency and data-driven decision making (Brown et al., 2021). The implementation of a CRM-based approach to community support management presents a novel application of proven business management principles to the humanitarian sector.

The project also addresses the United Nations Sustainable Development Goal 17 (Partnerships for the Goals) by creating a platform that facilitates partnerships between donors and recipients, ultimately contributing to poverty reduction and community development. The enhanced transparency and accountability provided by the system aligns with global best practices in humanitarian aid management and can serve as a model for similar initiatives in other regions.

## 1.6 Scope

The scope of this project encompasses the design and implementation of a web-based Community Support Management System for managing donations, victim registrations, and fund distributions within communities in Kenya. The system will include the following key components:

**User Management:** The system will support three primary user roles: administrators, donors, and victims. Each role will have specific access permissions and functionality tailored to their needs. Administrators will have full system access for managing users, approving victim applications, and distributing funds. Donors will be able to register, make donations, view donation history, and track the impact of their contributions. Victims will be able to apply for assistance, view application status, and track received donations.

**Donation Management:** The system will support both direct donations to specific victims and contributions to a general pool fund. Donations can be made through various payment methods including cash and mobile money (M-Pesa). The system will maintain comprehensive records of all donations including donor information, amount, date, payment method, and recipient details.

**Victim Application Management:** Individuals seeking assistance can submit applications through the system, providing necessary information for verification purposes. Administrators can review and approve or reject applications based on established criteria. Approved victims become eligible to receive distributions from the general pool fund.

**Fund Distribution:** Administrators can distribute funds from the general pool to approved victims based on need and available resources. The system will maintain records of all distributions including recipient information, amount, date, and distribution notes.

**Reporting and Analytics:** The system will provide comprehensive_reporting capabilities for administrators, including total donations, total distributions, available funds, donor statistics, and victim statistics. Donors will be able to view their personal donation history and track the impact of their contributions.

**Security and Access Control:** The system will implement role-based access control to ensure users can only access functionality appropriate to their role. User authentication will be implemented using secure password hashing and session management.

The project scope excludes integration with external financial institutions beyond M-Pesa, advanced analytics using machine learning, and mobile application development. The system will be designed for use within Kenya and may require modifications for deployment in other regions with different payment infrastructures.

## 1.7 Research Organization

This project report is organized into six chapters as follows:

**Chapter 1: Introduction** - Provides the background of the study, problem statement, objectives, justification, scope, and organization of the research.

**Chapter 2: Review of Related Work** - Presents a comprehensive review of existing literature, related systems, and emerging trends in community support management and CRM systems. This chapter also identifies the research gap that this project addresses.

**Chapter 3: Research Methodology** - Describes the methodologies used for requirement specification, system analysis, system design, implementation, testing, and deployment. This chapter outlines the tools and techniques employed throughout the project development lifecycle.

**Chapter 4: System Analysis** - Provides a detailed analysis of the current system, including its strengths and weaknesses. This chapter presents the feasibility study, requirements analysis, system analysis using Structured Systems Analysis and Design (SSAD) tools, and normalization of database entities.

**Chapter 5: System Design** - Presents the design of the proposed system, including the conceptual architecture, process design using flowcharts, database design with Entity Relationship Diagrams (ERD) and data dictionary, and input/output design with mock-up screens.

**Chapter 6: Implementation** - Describes the implementation of the system, including user interface implementation, database implementation, process design implementation, and module testing. This chapter also presents test results and system performance evaluation.

The report concludes with references and appendices containing supplementary materials such as sample questionnaires, interviews, budget schedules, time schedules, and sample code.
