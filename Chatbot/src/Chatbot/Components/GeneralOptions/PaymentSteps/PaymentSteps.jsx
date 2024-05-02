import React from 'react';
import './PaymentSteps.css';
import PaymentIcon from './Images/payment-icon.png';
import PayNow from './Images/pay-now.png';
import SignIn from './Images/sign-in.png';
import TermAmount from './Images/term-amount.png';
import PayButton from './Images/pay-button.png';

const PaymentSteps = () => {
    return <div className='payment-widget-container'>
        <ul className='payment-widget-list'>
        <li className='payment-widget-list-item'>Visit the <a href='https://aau.ac.ae/e-services' placeholder="Al Ain University E-Services" target="_blank" rel="noreferrer">E-Services</a> webpage.</li>
        <li className='payment-widget-list-item'>Choose 'Student Online Payment'.<img className="payment-image" src={PaymentIcon} alt='Online Payment icon' /></li>
        <li className='payment-widget-list-item'>Sign in as prompted using your AAU student account.<img className="payment-image" src={SignIn} alt ='student sign-in' /></li>
        <li className='payment-widget-list-item'>Click on 'Pay Now' in the top-right corner.<img className="payment-image" src={PayNow} alt='Pay Now button' /></li>
        <li className='payment-widget-list-item'>Select the current term and input the desired amount, then click 'Pay Now' below.<img className="payment-image" src={TermAmount} alt='Term selection'/></li>
        <li className='payment-widget-list-item'>Finally, fill the billing and payment details, then scroll down to find the green 'Pay' button at the bottom.<img className="payment-image" src={PayButton} alt='Pay button'/></li>
        </ul>
    </div>
};

export default PaymentSteps;