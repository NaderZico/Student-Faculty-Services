import React from 'react';
import './AAU.css';
import ADCampus from './AAU_AD.jpg';

const AAU = () => {
    return <div className='AAU-widget-container'>
        <h2 className='AAU-widget-header'><a href='https://www.aau.ac.ae/' placeholder="Al Ain University Website" target="_blank" rel="noreferrer">AL AIN UNIVERSITY</a></h2>
        <div className='AAU-widget-body'>
            <img className="AAU-image" src={ADCampus} alt='Al Ain University Abu Dhabi Campus' />
            <p className='AAU-widget-paragraph'>
                The AAU is now offering 22 and expanding undergraduate programs spanning six colleges,
                including Engineering, Pharmacy, Law, Education, Humanities, Social Sciences, Business, Communication, and Media.
                Additionally, AAU provides 8 Master’s programs and a Professional Diploma in Teaching.
            </p>
            <iframe 
                className = "AAU-location"
                title="AAU AD campus location"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3635.0245496278276!2d54.53663777523019!3d24.345637478268912!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3e5e69cd8eb83b6b%3A0x3e1ad8d50f67a52b!2sAl%20Ain%20University%20-%20Abu%20Dhabi%20Campus!5e0!3m2!1sen!2sae!4v1705925004658!5m2!1sen!2sae"
                loading="lazy"
                referrerPolicy="no-referrer-when-downgrade"
            ></iframe>
        </div>
    </div>
};

export default AAU;