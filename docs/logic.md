# Business Logic & Workflow

This document explains the core business logic and workflows implemented in ProjectX.

## 1. Diagnosis Workflow

The diagnosis process is a critical part of the system, primarily handled by doctors.

### Workflow Steps:
1.  **Consultation**: During an appointment, the doctor assesses the patient.
2.  **Data Capture**: The doctor enters clinical findings, including vitals (BP, Temp), a condition name, and severity.
3.  **Clinical Coding**: The system supports standard clinical coding (ICD-10 or SNOMED) to ensure data interoperability.
4.  **Status Management**: Diagnoses can be 'Preliminary' or 'Final'.
5.  **Resolution**: Conditions can be marked as 'Resolved', which moves them from 'Active Problems' to the patient's 'Medical History'.

### Validation Logic:
- Vitals require specific formats (e.g., BP as `systolic/diastolic`).
- A condition name is mandatory for any diagnosis entry.

## 2. Appointment Scheduling

[Explain your appointment logic here, e.g., how slot availability is determined, cancellation policies, etc.]

## 3. Real-time Chat

[Explain how chat authorization works, how messages are persisted, and how real-time updates are pushed via Reverb.]

## 4. Role-Based Access Control (RBAC)

The system enforces roles to protect sensitive medical data:
- **Admin**: Full system management, doctor registration.
- **Doctor**: Can create diagnoses, view assigned patient history, and manage appointments.
- **Patient/User**: Can book appointments and view their own medical records.

---

> [!TIP]
> For complex algorithms, consider using flowcharts or sequence diagrams to visualize the logic.
