-- User accounts and details for login credentials
CREATE TABLE user_accounts (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    age INT,
    gender VARCHAR(50),
    email VARCHAR(50) UNIQUE,
    address VARCHAR(250),
    nationality VARCHAR(50),
    password TEXT NOT NULL,
    role ENUM('applicant', 'HR') NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


-- Job posts created by HR
CREATE TABLE JobPosts (
    job_id INT AUTO_INCREMENT PRIMARY KEY,
    hr_id INT NOT NULL,
    title VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hr_id) REFERENCES user_accounts(user_id)
);

-- Applications submitted by applicants
CREATE TABLE Applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    job_id INT NOT NULL,
    resume_path VARCHAR(255) NOT NULL,
    application_status ENUM('Pending', 'Accepted', 'Rejected') DEFAULT 'Pending',
    cover_letter TEXT NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (applicant_id) REFERENCES user_accounts(user_id),
    FOREIGN KEY (job_id) REFERENCES JobPosts(job_id)
);

-- Messages exchanged between applicants and HR
CREATE TABLE Messages (
    message_id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES user_accounts(user_id),
    FOREIGN KEY (receiver_id) REFERENCES user_accounts(user_id)
);
