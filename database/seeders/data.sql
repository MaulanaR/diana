INSERT INTO `users` VALUES ('1', 'Administrator', 'Administrator', 'admin@admin.com', null, '\$2y\$10\$OZvPqz9tcUPSQRIGJwhx7eEoGEEyWDlezlGDiGzPUEQmCRvycm8DS', 'chika7.jpg', null, null, '2022-09-28 00:55:58', '2022-09-28 00:36:42', '2022-09-28 00:55:58', '127.0.0.1');
INSERT INTO `users` VALUES ('2', 'Students Nih', 'Students Nih', 'student@admin.com', null, '\$2y\$10\$lOOfS4Ic.eoSJELAJK0G6u5izsnHUbk.ttqHR68U58KDc8yZzRFp.', 'chika3.jpg', null, '2022-09-16 21:06:05', '2022-09-22 23:02:42', '2022-09-18 00:08:20', '2022-09-22 21:38:13', '127.0.0.1');
INSERT INTO `users` VALUES ('3', 'Instructor', 'Instructor Asep', 'instructor@admin.com', null, '\$2y\$10\$OZvPqz9tcUPSQRIGJwhx7eEoGEEyWDlezlGDiGzPUEQmCRvycm8DS', 'user.png', null, '2022-09-16 21:07:28', '2022-09-28 00:56:20', '2022-09-28 00:37:15', '2022-09-28 00:56:20', '127.0.0.1');
INSERT INTO `users` VALUES ('7', 'Student 2', 'Bambang', 'student2@admin.com', null, '\$2y\$10\$AEQ2Jg2kK3MDohUbe1Odx.SbfoVVKx8gwN0T/4eNz7z/ZrqYdYSg6', 'WIN_20191021_14_47_48_Pro.jpg', null, '2022-09-22 23:22:53', '2022-09-27 22:24:37', '2022-09-22 23:27:03', '2022-09-27 22:24:37', '127.0.0.1');
INSERT INTO `alus_g` VALUES ('17', 'admin', 'Admin Website');
INSERT INTO `alus_g` VALUES ('18', 'student', 'Students Roles');
INSERT INTO `alus_g` VALUES ('19', 'instructor', 'Instructor Rules');
INSERT INTO `alus_mg` VALUES ('53', '0', 'Menus', 'menus', null, 'fas fa-allergies', '1', null, null);
INSERT INTO `alus_mg` VALUES ('54', '0', 'Roles', 'group', null, 'fas fa-align-justify', '2', null, '2022-09-15 20:33:17');
INSERT INTO `alus_mg` VALUES ('55', '0', 'Users', 'users', null, 'fas fa-allergies', '3', null, null);
INSERT INTO `alus_mg` VALUES ('56', '0', 'Interships', '#', null, 'fas fa-align-justify', '1', '2022-09-15 21:42:25', '2022-09-15 21:42:25');
INSERT INTO `alus_mg` VALUES ('57', '56', 'Internship Locations', 'internship_locations', null, 'fas fa-info-circle', '1', '2022-09-15 21:43:27', '2022-09-15 21:43:27');
INSERT INTO `alus_mg` VALUES ('58', '56', 'Internship Periods', 'internship_periods', null, 'fas fa-award', '0', '2022-09-15 21:44:08', '2022-09-15 21:44:08');
INSERT INTO `alus_mg` VALUES ('59', '56', 'Interships Students', 'internship_students', null, 'fas fa-align-justify', '3', '2022-09-15 21:44:36', '2022-09-16 21:02:58');
INSERT INTO `alus_mg` VALUES ('60', '0', 'Academic Data', '#', null, 'fas fa-book', '1', '2022-09-25 22:30:35', '2022-09-25 23:01:40');
INSERT INTO `alus_mg` VALUES ('61', '60', 'Academic Periods', 'academic_periods', null, 'fas fa-calendar-alt', '1', '2022-09-25 22:30:54', '2022-09-25 23:01:49');
INSERT INTO `alus_mg` VALUES ('62', '60', 'Majors', 'majors', null, 'fas fa-atom', '1', '2022-09-25 23:08:36', '2022-09-25 23:08:36');
INSERT INTO `alus_mg` VALUES ('63', '60', 'Classes', 'classes', null, 'fas fa-bars', '2', '2022-09-25 23:40:52', '2022-09-25 23:40:52');
INSERT INTO `alus_mg` VALUES ('64', '60', 'Courses', 'courses', null, 'fas fa-atom', '6', '2022-09-26 21:59:02', '2022-09-26 21:59:02');
INSERT INTO `alus_mga` VALUES ('2973', '18', '53', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2974', '18', '54', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2975', '18', '55', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2976', '18', '56', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2977', '18', '57', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2978', '18', '58', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('2979', '18', '59', '1', '1', '1', '0');
INSERT INTO `alus_mga` VALUES ('3017', '17', '53', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3018', '17', '54', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3019', '17', '55', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3020', '17', '56', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3021', '17', '57', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3022', '17', '58', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3023', '17', '59', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3024', '17', '60', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3025', '17', '61', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3026', '17', '62', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3027', '17', '63', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3028', '17', '64', '1', '1', '1', '1');
INSERT INTO `alus_mga` VALUES ('3029', '19', '53', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3030', '19', '54', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3031', '19', '55', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3032', '19', '56', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3033', '19', '57', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3034', '19', '58', '0', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3035', '19', '59', '1', '1', '1', '0');
INSERT INTO `alus_mga` VALUES ('3036', '19', '60', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3037', '19', '61', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3038', '19', '62', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3039', '19', '63', '1', '0', '0', '0');
INSERT INTO `alus_mga` VALUES ('3040', '19', '64', '1', '0', '0', '0');
INSERT INTO `alus_ug` VALUES ('40', '1', '17');
INSERT INTO `alus_ug` VALUES ('36', '2', '18');
INSERT INTO `alus_ug` VALUES ('35', '3', '19');
INSERT INTO `alus_ug` VALUES ('45', '7', '18');
INSERT INTO `alus_ug` VALUES ('46', '7', '19');
INSERT INTO `instructors` VALUES ('3', 'Instructor Asep', '2022-09-16 21:07:54', null, '2022-09-28 00:36:08', null, null, 'Male', null, '2022-09-28asdas', 'Menikah', null, null, null, null, null, null, null, null, null, null, 'instructor@admin.com', null, null, null, null, 'ss', null, null, null, null);
INSERT INTO `instructors` VALUES ('7', 'Student 2', '2022-09-22 23:25:32', null, '2022-09-22 23:25:32', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
INSERT INTO `student_details` VALUES ('2', 'Diana Diana', null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, '2022-09-16 21:06:58', null, null, null, null);
INSERT INTO `student_details` VALUES ('7', 'Bambang', '123', '2022-09-24', 'male', 'Islam', '123', '123', '123', '123', '1', '0', '0', '123', '1231', '123', '123', '123', '123', '123', '123', '123', 'WIN_20191021_14_47_48_Pro.jpg', null, null, null, null, '2022-09-22 23:22:53', null, '2022-09-25 00:17:21', null, null);