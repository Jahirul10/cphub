INSERT INTO students (id, name, phone, session)
VALUES ('1810876192','John Doe', '555-1234', '2017-18'),
('1810876193','John Doe', '555-1234', '2017-18'),
('1810876194','John Doe', '555-1234', '2017-18');

INSERT INTO problems (id, title, url, oj)
VALUES ('1791e','Negatives and Positives', 'https://codeforces.com/contest/1791/problem/E', 'codeforces'),
('1454c','Sequence Transformation', 'https://codeforces.com/contest/1454/problem/C', 'codeforces'),
('580b','Kefa and Company', 'https://codeforces.com/contest/580/problem/B', 'codeforces');

INSERT INTO submissions (id, problem_id, language, submissiontime, verdict, student_id)
VALUES ('191959605','1791e', 'c++', '2023-02-27 15:35:20.311', 'accepted', '1810876192'),
('191951206','1454c', 'java', '2023-02-27 15:35:20.311', 'accepted','1810876194'),
('191941537','580b', 'python', '2023-02-27 15:35:20.311', 'wrong_answer', '1810876194');

INSERT INTO handles (id, cfhandle, vjhandle, spojhandle)
VALUES ('1810876192','ABIR', 'MD_ABIR', 'abuhanif'),
('1810876193','Benq', 'Heart_Blue', 'defrager');