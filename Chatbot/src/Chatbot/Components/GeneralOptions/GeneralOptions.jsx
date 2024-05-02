import React from "react";
import "./GeneralOptions.css";

const GeneralOptions = (props) => {
  const options = [
    { text: "Register Courses", handler: props.actionProvider.handleCourseRegistration, id: 1 },
    { text: "Conduct Payment", handler: props.actionProvider.handlePaymentSteps, id: 2 },
    { text: "Student Self-Service Guide", handler: props.actionProvider.handleStudentGuide, id: 3 },
    { text: "SFS User Guide", handler: props.actionProvider.handleUserGuide, id: 4 },
  ];

  const optionsMarkup = options.map((option) => (
    <button
      className="general-option-button"
      key={option.id}
      onClick={option.handler}
    >
      {option.text}
    </button>
  ));

  return <div className="general-options-container">{optionsMarkup}</div>;
};

export default GeneralOptions;