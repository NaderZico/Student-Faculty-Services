import React, { useState } from 'react';
import parseCourse from './CourseMessageParser';
import parseFaculty from './FacultyMessageParser';

const MessageParser = ({ children, actions }) => {
  const [course, setSelectedCourse] = useState();
  const [faculty, setSelectedFaculty] = useState();

  const parse = (message) => {
    const lowercaseMessage = message.toLowerCase().trim();

    if (lowercaseMessage === 'hello' || lowercaseMessage === 'hi' || lowercaseMessage === 'greetings') {
      actions.handleHello();
    }
    else if (lowercaseMessage.includes('options') || lowercaseMessage.includes('services')
      || lowercaseMessage.includes('help') || lowercaseMessage.includes('support')
      || lowercaseMessage.includes('question')) {
      actions.handleMainOptions();
    } else if (lowercaseMessage.includes('your name') || lowercaseMessage.includes('who are you')) {
      actions.handleName();
    } else if (lowercaseMessage.includes('aau') || lowercaseMessage.includes('al ain university') || lowercaseMessage.includes('location')) {
      actions.handleAAU();
    } else if (lowercaseMessage.includes('register') || lowercaseMessage.includes('registration') || lowercaseMessage.includes('enroll')) {
      actions.handleCourseRegistration();
    }
    else if (lowercaseMessage.includes('pay') || lowercaseMessage.includes('billing')) {
      actions.handlePaymentSteps();
    }
    else if (lowercaseMessage.includes('thank') || lowercaseMessage.includes('thx') || lowercaseMessage.includes('appreciate')
             || lowercaseMessage.includes('ty')) {
      actions.handleThankYou();
    }
    else if (lowercaseMessage.includes('how are you')) {
  actions.handleHowAreYou();

} else {
  const courseInput = parseCourse(message, actions, setSelectedCourse);
  const facultyInput = parseFaculty(message, actions, setSelectedFaculty);

  if (!courseInput && !facultyInput) {
    actions.handleError();
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
