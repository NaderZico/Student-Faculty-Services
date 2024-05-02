import React from 'react';
import courseData from './course.json'; 
import './SimilarCoursesMessage.css';

const SimilarCoursesMessage = ({ similarCourses, onSelectCourse }) => {
    const courses = courseData.find((item) => item.type === 'table' && item.name === 'course');
  
    const handleCourseInput = (courseName) => {
        const selectedCourse = courses.data.find(course => course.course_name.toLowerCase() === courseName);
        
        if (selectedCourse) {
            onSelectCourse(selectedCourse); 
        } else {
            console.error(`Course '${courseName}' not found.`);
        }
    };

    return (
        <div className="similar-courses-container">
            <p className="similar-courses-question">Which course are you referring to?</p>
            <ul className="similar-courses-list">
                {similarCourses.map((course, index) => (
                    <li key={index} className="similar-courses-list-item" onClick={() => handleCourseInput(course.course)}>
                        {course.course}
                    </li>
                ))}
            </ul>
        </div>
    );
};

export default SimilarCoursesMessage;
