import React from 'react';
import './CourseRegistration.css';
import SelfService from './Images/self-service.png';
import RegisterClasses from './Images/register-classes.png';
import SearchTool from './Images/search-tool.png';
import TermSelect from './Images/term-select.png';
import AddCourse from './Images/add-course.png';
import SignIn from './Images/sign-in.png';

const CourseRegistration = () => {
    return <div className='registration-widget-container'>
        <ul className='registration-widget-list'>
        <li className='registration-widget-list-item'>After conducting your payment during the registration period, visit the <a href='https://aau.ac.ae/e-services' placeholder="Al Ain University E-Services" target="_blank" rel="noreferrer">E-Services</a> webpage.</li>
        <li className='registration-widget-list-item'>Choose 'Student Self-Service'.<img className="registration-image" src={SelfService} alt='Student Self-Service icon' /></li>
        <li className='registration-widget-list-item'>Choose 'Register for Classes'.<img className="registration-image" src={RegisterClasses} alt='Register for classes' /></li>
        <li className='registration-widget-list-item'>Sign in as prompted using your AAU student account. <img className="registration-image" src={SignIn} alt='Student account sign-in' /></li>
        <li className='registration-widget-list-item'>Select the term for registration <img className="registration-image" src={TermSelect} alt='Term selection tool'/></li>
        <li className='registration-widget-list-item'>Use the search tool to find courses.<img className="registration-image" src={SearchTool} alt='Course Search form'/></li>
        <li className='registration-widget-list-item'>Click 'Add' to register the desired course.<img className="registration-image" src={AddCourse} alt='Adding a course'/></li>
        </ul>
    </div>
};

export default CourseRegistration;