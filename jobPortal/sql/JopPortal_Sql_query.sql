
CREATE DATABASE jobportal;


USE jobportal;


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'recruiter', 'admin') NOT NULL,
    full_name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE jobs (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company_name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL, 
    posted_by INT NOT NULL,
    vacancy_date DATE NOT NULL,
    FOREIGN KEY (posted_by) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM('applied', 'reviewed', 'accepted', 'rejected') DEFAULT 'applied',
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


CREATE TABLE feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    message TEXT NOT NULL,
    submitted_by ENUM('user', 'recruiter') NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);


INSERT INTO users (email, password, role, full_name, phone) 
VALUES ('admin@jobportal.com', 'admin123', 'admin', 'Admin', '1234567890');


INSERT INTO users (email, password, role, full_name, phone) 
VALUES ('recruiter@company.com', 'recruiter123', 'recruiter', 'Recruiter', '0987654321');


INSERT INTO users (email, password, role, full_name, phone) 
VALUES ('user@jobportal.com', 'user123', 'user', 'Job Seeker', '5555555555');


INSERT INTO jobs (title, company_name, description, location, posted_by, vacancy_date) 
VALUES ('Software Engineer', 'TechCorp', 'Looking for a full-stack developer.', 'New York, NY', 2, '2024-12-31');

INSERT INTO jobs (title, company_name, description, location, posted_by, vacancy_date) 
VALUES ('Data Analyst', 'DataCorp', 'Looking for an experienced data analyst.', 'San Francisco, CA', 2, '2024-11-30');

ALTER TABLE applications
ADD resume_link VARCHAR(255) AFTER user_id;

ALTER TABLE applications
ADD skills VARCHAR(255) AFTER resume_link;


ALTER TABLE feedback
ADD COLUMN job_id INT NULL,
ADD CONSTRAINT fk_job_id
    FOREIGN KEY (job_id) REFERENCES jobs(job_id) ON DELETE SET NULL;


ALTER TABLE feedback
ADD COLUMN submitted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP;



