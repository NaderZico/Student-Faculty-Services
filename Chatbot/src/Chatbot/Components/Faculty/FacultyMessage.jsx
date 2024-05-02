import React from 'react';
import './FacultyMessage.css';

const FacultyMessage = ({ faculty }) => {
    return (
      <div className='faculty-widget'>
        <ul className='faculty-widget-list'>
          <li className='faculty-widget-list-item'><strong>Name:</strong> {faculty.faculty_name}</li>
          <li className='faculty-widget-list-item'><strong>Email:</strong> <a href={`mailto:${faculty.email}`}>{faculty.email}</a></li>
          <li className='faculty-widget-list-item'><strong>Department:</strong> {faculty.department}</li>
         </ul>
      </div>
    );
  };
  
  export default FacultyMessage;