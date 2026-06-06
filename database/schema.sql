CREATE DATABASE IF NOT EXISTS clinicdesk_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE clinicdesk_db;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(180) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','doctor','patient') NOT NULL DEFAULT 'patient',
  phone VARCHAR(20) DEFAULT NULL,
  avatar VARCHAR(255) DEFAULT NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE specializations (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL UNIQUE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE doctors (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL UNIQUE,
  specialization_id INT UNSIGNED NOT NULL,
  bio TEXT DEFAULT NULL,
  photo VARCHAR(255) DEFAULT NULL,
  consultation_fee DECIMAL(8,2) NOT NULL DEFAULT 0.00,
  available_days VARCHAR(50) NOT NULL DEFAULT 'Sun,Mon,Tue,Wed,Thu',
  FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (specialization_id) REFERENCES specializations(id) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE appointments (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  patient_id INT UNSIGNED NOT NULL,
  doctor_id INT UNSIGNED NOT NULL,
  appt_date DATE NOT NULL,
  appt_time TIME NOT NULL,
  status ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  reason VARCHAR(255) DEFAULT NULL,
  doctor_notes TEXT DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  UNIQUE KEY no_double_booking (doctor_id, appt_date, appt_time),
  FOREIGN KEY (patient_id) REFERENCES users(id) ON DELETE CASCADE,
  FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE prescriptions (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  appointment_id INT UNSIGNED NOT NULL UNIQUE,
  diagnosis TEXT NOT NULL,
  medications TEXT NOT NULL,
  notes TEXT DEFAULT NULL,
  file_path VARCHAR(255) DEFAULT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, password, role) VALUES
('Admin', 'admin@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'admin'),
('Dr. Lina Hassan', 'doctor@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'doctor'),
('Patient Demo', 'patient@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'patient'),
('Dr. Omar Nasser', 'omar.nasser@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'doctor'),
('Aya Mansour', 'aya.mansour@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'patient'),
('Sami Khaled', 'sami.khaled@clinic.local', '$2y$12$8UI5u1AsLGJX3p27lyutX.0nJa0Vt9X8hknLzzjoE4NhxTyU1/hPa', 'patient');

INSERT INTO specializations (name) VALUES
('General Practice'),('Cardiology'),('Dermatology'),('Pediatrics'),('Orthopedics'),('Neurology'),('Ophthalmology'),('ENT'),('Psychiatry');

INSERT INTO doctors (user_id, specialization_id, bio, consultation_fee, available_days)
VALUES
(2, 1, 'General practitioner with outpatient clinic experience.', 35.00, 'Sun,Mon,Tue,Wed,Thu'),
(4, 2, 'Cardiologist focused on preventive care and follow-up visits.', 55.00, 'Sun,Tue,Thu');

INSERT INTO appointments (patient_id, doctor_id, appt_date, appt_time, status, reason, doctor_notes, created_at) VALUES
(3, 1, '2026-05-14', '09:00:00', 'completed', 'Routine follow-up after flu symptoms.', 'Patient improved. Continue fluids and rest.', '2026-05-10 08:30:00'),
(5, 2, '2026-05-19', '10:30:00', 'completed', 'Chest discomfort and blood pressure follow-up.', 'Blood pressure controlled. ECG reviewed.', '2026-05-15 11:00:00'),
(6, 1, '2026-05-21', '11:00:00', 'completed', 'Headache and sinus pressure.', 'Mild sinusitis signs. Follow medication plan.', '2026-05-17 09:15:00'),
(5, 2, '2026-06-11', '09:30:00', 'confirmed', 'Cardiology follow-up visit.', NULL, '2026-06-01 10:10:00'),
(5, 1, '2026-05-26', '09:30:00', 'completed', 'Seasonal allergy symptoms and throat irritation.', 'Mild allergic rhinitis. Medication plan explained.', '2026-05-23 12:20:00'),
(5, 2, '2026-05-28', '11:30:00', 'completed', 'Review blood pressure readings after medication.', 'Readings improved. Continue treatment and lifestyle plan.', '2026-05-25 09:45:00');

INSERT INTO prescriptions (appointment_id, diagnosis, medications, notes, file_path, created_at) VALUES
(1, 'Post-viral fatigue with improving flu symptoms.', 'Paracetamol 500mg when needed; oral rehydration salts once daily for 3 days.', 'Return if fever comes back or symptoms worsen.', 'prescription_1_seed.pdf', '2026-05-14 09:25:00'),
(2, 'Controlled hypertension with non-cardiac chest discomfort.', 'Amlodipine 5mg once daily; low-salt diet; monitor blood pressure twice weekly.', 'Schedule follow-up if chest pain becomes severe or persistent.', 'prescription_2_seed.pdf', '2026-05-19 10:55:00'),
(3, 'Acute mild sinusitis.', 'Saline nasal spray three times daily; cetirizine 10mg at night for 5 days.', 'Increase fluids and avoid dust exposure.', 'prescription_3_seed.pdf', '2026-05-21 11:20:00'),
(5, 'Seasonal allergic rhinitis.', 'Loratadine 10mg once daily for 7 days; saline gargle twice daily.', 'Avoid dust exposure and return if breathing difficulty appears.', 'prescription_5_seed.pdf', '2026-05-26 09:55:00'),
(6, 'Hypertension follow-up with improved readings.', 'Continue Amlodipine 5mg once daily; check blood pressure every morning.', 'Bring pressure log to the next cardiology visit.', 'prescription_6_seed.pdf', '2026-05-28 12:00:00');
