import React from "react";
import "./Options.css";

const Options = (props) => {
  const options = [
    { text: "Al Ain University", handler: props.actionProvider.handleAAU, id: 1 },
    { text: "Course Info", handler: props.actionProvider.handleCourseOption, id: 2 },
    { text: "Contact Faculty", handler: props.actionProvider.handleContactOption, id: 3 },
    { text: "General Help", handler: props.actionProvider.handleGeneralOptions, id: 4 }, 
  ];

  const optionsMarkup = options.map((option) => (
    <button
      className="option-button"
      key={option.id}
      onClick={option.handler}
    >
      {option.text}
    </button>
  ));

  return <div className="options-container">{optionsMarkup}</div>;
};

export default Options;