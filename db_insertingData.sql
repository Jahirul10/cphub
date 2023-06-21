DELETE FROM problems;
DELETE FROM submissions;
UPDATE handles SET cf_last_submission = 0, vj_last_submission = 0, spoj_last_submission=0;

INSERT INTO students (id, name, phone, session)
VALUES ('1810876114','A', '555-1234', '2018-19'),
('1810876112','A', '555-1234', '2018-19'),
('1810876113','A', '555-1234', '2018-19');
INSERT INTO handles (id, cfhandle, vjhandle, spojhandle, cf_last_submission, spoj_last_submission, vj_last_submission)
VALUES ('1810876112','Benq', 'zby0327', 'zukow', 0, 0, 0),
('1810876113','tourist', 'tigerisland45', 'xilinx', 0, 0, 0);

INSERT INTO students (id, name, phone, session)
VALUES ('1810876192','John Doe', '555-1234', '2017-18'),
('1810876193','John Doe', '555-1234', '2017-18'),
('1810876194','John Doe', '555-1234', '2017-18'),
('1810876177','John Doe', '555-1234', '2017-18');

INSERT INTO problems (id, title, url, oj)
VALUES ('1791e','Negatives and Positives', 'https://codeforces.com/contest/1791/problem/E', 'codeforces'),
('1454c','Sequence Transformation', 'https://codeforces.com/contest/1454/problem/C', 'codeforces'),
('580b','Kefa and Company', 'https://codeforces.com/contest/580/problem/B', 'codeforces');

INSERT INTO submissions (id, problem_id, language, submissiontime, verdict, student_id, submission_id)
VALUES ('191959605','1791e', 'c++', '2023-02-27 15:35:20.311', 'accepted', '1810876192'),
('191951206','1454c', 'java', '2023-02-27 15:35:20.311', 'accepted','1810876194'),
('191941537','580b', 'python', '2023-02-27 15:35:20.311', 'wrong_answer', '1810876194');

INSERT INTO handles (id, cfhandle, vjhandle, spojhandle, cf_last_submission, spoj_last_submission, vj_last_submission)
VALUES ('1810876124','hasibulfor', 'hasibulfor', 'hasibulfor', 0, 0, 0),
('1810876141','ABIR', 'MD_ABIR', 'abuhanif', 0, 0, 0),
('1810876145','jahirul', 'jahirul_islam1', 'true_false_0', 0, 0, 0)