import React from 'react';
import './CourseMessage.css';

const CourseMessage = ({ course }) => {
  const prereqCourses = course.pre_requisite.split('\r\n');

  const nextCourses = course.post_requisite.split('\r\n');

  return (
    <div className='course-widget'>
      <ul className='course-widget-list'>
        <li className='course-widget-list-item'><strong>Course ID:</strong> {course.course_id}</li>
        <li className='course-widget-list-item'><strong>Name:</strong> {course.course_name}</li>
        <li className='course-widget-list-item'><strong>Major:</strong> {course.course_major}</li>
        <li className='course-widget-list-item'><strong>Description:</strong>
          <p className='course-description'> {course.course_description}</p></li>
        <li className='course-widget-list-item'><strong>Pre-requisite:</strong>
          <ul className='course-widget-requisite'>
            {prereqCourses.map((course, index) => (
              <li key={index}>{course}</li>
            ))}
          </ul>
        </li>
        <li className='course-widget-list-item'><strong>Proceeding Course/s:</strong>
          <ul className='course-widget-requisite'>
            {nextCourses.map((course, index) => (
              <li key={index}>{course}</li>
            ))}
          </ul>
        </li>
      </ul>
    </div>
  );
};

export default CourseMessage;
