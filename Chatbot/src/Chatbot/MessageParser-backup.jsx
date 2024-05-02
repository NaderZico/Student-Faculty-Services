import React, { useState } from 'react';
import courseData from './Components/Course/course.json';
import facultyData from './Components/Faculty/faculty.json';

const MessageParser = ({ children, actions }) => {
  const [course, setSelectedCourse] = useState();
  const [faculty, setSelectedFaculty] = useState();

  const parse = (message) => {
    const lowerCase = message.toLowerCase().trim();

    if (lowerCase.includes('hello') || lowerCase.includes('hi')) {
      actions.handleHello();
    } else if (lowerCase.includes('your name')) {
      actions.handleName();
    } else if (lowerCase.includes('options') || lowerCase.includes('services') || lowerCase.includes('help') || lowerCase.includes('support')) {
      actions.handleMainOptions();
    } else if (lowerCase.includes('aau') || lowerCase.includes('al ain university')) {
      actions.handleAAU();
    } else if (lowerCase.includes('register') || lowerCase.includes('registration')) {
      actions.handleCourseRegistration();
    } else if (lowerCase.includes('pay') || lowerCase.includes('payment')) {
      actions.handlePaymentSteps();
    } else {
      // Check if the message is related to a course
      const digitsOnly = /\d{6}/;
      const [inputCourseID] = message.match(digitsOnly) || [];
      const courses = courseData.find((item) => item.type === 'table' && item.name === 'course');
      const courseById = courses?.data.find((course) => course.course_id === inputCourseID);
      const courseByName = courses?.data.find((course) => course.course_name.toLowerCase().trim() === lowerCase);
      const selectedCourse = courseById || courseByName;

      if (selectedCourse) {
        setSelectedCourse(selectedCourse);
        actions.handleCourseInput(selectedCourse);
      } else {
        // Check if the message is related to a faculty
        const faculty = facultyData.find((item) => item.type === 'table' && item.name === 'a');
        const facultyByName = faculty?.data.find((faculty) => faculty.faculty_name.toLowerCase().trim() === lowerCase);
        if (facultyByName) {
          setSelectedFaculty(facultyByName);
          actions.handleFacultyInput(facultyByName);
        } else {
          actions.handleError();
        }
      }
    }
  };

  return (
    <div>
      {React.Children.map(children, (child) => {
        return React.cloneElement(child, {
          parse: parse,
          actions,
          course,
          faculty,
        });
      })}
    </div>
  );
};

export default MessageParser;
