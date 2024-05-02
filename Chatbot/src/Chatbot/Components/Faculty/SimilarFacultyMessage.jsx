import React from 'react';
import facultyData from './faculty.json';
import './SimilarFacultyMessage.css';

const SimilarFacultyMessage = ({ similarFaculty, onSelectFaculty }) => {
    // Find the faculty data
    const faculties = facultyData.find((item) => item.type === 'table' && item.name === 'a');

    const handleFacultyInput = (facultyName) => {
        // Find the selected faculty by name in the faculty data
        const selectedFaculty = faculties.data.find(faculty => faculty.faculty_name.toLowerCase() === facultyName);

        // Check if the selected faculty is found
        if (selectedFaculty) {
            onSelectFaculty(selectedFaculty); // Pass the selected faculty to the parent component
        } else {
            console.error(`Faculty '${facultyName}' not found.`);
        }
    };

    return (
        <div className="similar-faculty-container">
            <p className="similar-faculty-question">Which faculty are you referring to?</p>
            <ul className="similar-faculty-list">
                {similarFaculty.map((faculty, index) => (
                    <li key={index} className="similar-faculty-list-item" onClick={() => handleFacultyInput(faculty.faculty)}>
                        {faculty.faculty}
                    </li>
                ))}
            </ul>
        </div>
    );
};
export default SimilarFacultyMessage;
