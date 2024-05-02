import React from 'react';
import CourseMessage from './Components/Course/CourseMessage.jsx';
import SimilarCoursesMessage from './Components/Course/SimilarCoursesMessage.jsx';
import SimilarFacultyMessage from './Components/Faculty/SimilarFacultyMessage.jsx';
import FacultyMessage from './Components/Faculty/FacultyMessage.jsx';
import StudentGuide from './Components/GeneralOptions/StudentGuide/StudentGuide.jsx';
import UserGuide from './Components/GeneralOptions/UserGuide/UserGuide.jsx';

const ActionProvider = ({ createChatBotMessage, setState, children }) => {

  const handleError = () => {
    const botMessage = createChatBotMessage("I don't understand your message. Please make sure to use one of our provided services.", {
      widget: 'Options',
    });
    addBotMessage(botMessage);
  };

  const handleThankYou = () => {
    const botMessage = createChatBotMessage("You're very welcome! Feel free to type 'help' or 'support' at any time for further assistance.");
    addBotMessage(botMessage);
  }

  const handleHello = () => {
    const botMessage = createChatBotMessage('Hello! Nice to meet you.');
    addBotMessage(botMessage);
  };

  const handleHowAreYou = () => {
    const botMessage = createChatBotMessage("I'm doing great! Thanks for asking.");
    addBotMessage(botMessage);
  };

  const handleName = () => {
    const botMessage = createChatBotMessage('My name is Farah! Nice to meet you.');
    addBotMessage(botMessage);
  };

  const handleAAU = () => {
    const botMessage = createChatBotMessage('Click on the header for more info!', {
      widget: 'AAU',
    });
    addBotMessage(botMessage);
  };

  const handleMainOptions = () => {
    const botMessage = createChatBotMessage("We're here to assist! Below are the services we offer.", {
      widget: 'Options',
    });
    addBotMessage(botMessage);
  };

  const handleGeneralOptions = () => {
    const botMessage = createChatBotMessage("For info that every AAU student needs to know!", {
      widget: 'General',
    });
    addBotMessage(botMessage);
  };

  const handleCourseRegistration = () => {
    const botMessage = createChatBotMessage("Here's a simplified guide to register courses. Refer to the Self-Service Guide for more detailed steps.", {
      widget: 'CourseRegistration',
    });
    addBotMessage(botMessage);
  };

  const handlePaymentSteps = () => {
    const botMessage = createChatBotMessage("Here's a simplified guide to conduct your payment as a student.", {
      widget: 'PaymentSteps',
    });
    addBotMessage(botMessage);
  };

  const handleStudentGuide = () => {
    const botMessage = createChatBotMessage(<StudentGuide />);
    addBotMessage(botMessage);
  };

  const handleUserGuide = () => {
    const botMessage = createChatBotMessage(<UserGuide />);
    addBotMessage(botMessage);
  }

  const handleCourseOption = () => {
    const botMessage = createChatBotMessage("Please enter the course name or course ID.");
    addBotMessage(botMessage);
  };

  const handleCourseInput = (course) => {
    const botMessage = createChatBotMessage("Here's the course you requested.");
    const botMessage2 = createChatBotMessage(<CourseMessage course={course} />, {
      delay: 1000
    });
    addBotMessage(botMessage);
    addBotMessage(botMessage2);
  };

  const handleSimilarCourses = (similarCourses) => {
    const botMessage = createChatBotMessage(
      <SimilarCoursesMessage similarCourses={similarCourses} onSelectCourse={handleCourseInput} />
    );
    addBotMessage(botMessage);
  };

  const handleSimilarFaculty = (similarFaculty) => {
    const botMessage = createChatBotMessage(
    <SimilarFacultyMessage similarFaculty={similarFaculty} onSelectFaculty={handleFacultyInput} />
    );
    addBotMessage(botMessage);
  };

  const handleContactOption = () => {
    const botMessage = createChatBotMessage("Please enter the faculty member's full name.");
    addBotMessage(botMessage);
  }

  const handleFacultyInput = (faculty) => {
    const botMessage = createChatBotMessage("Here are the details of the instructor you requested.");
    const botMessage2 = createChatBotMessage(<FacultyMessage faculty={faculty} />, {
      delay: 1000
    });
    addBotMessage(botMessage);
    addBotMessage(botMessage2);
  };

  const addBotMessage = (message) => {
    setState((prev) => ({
      ...prev,
      messages: [...prev.messages, message]
    }));
  };

  return (
    <div>
      {React.Children.map(children, (child) => {
        return React.cloneElement(child, {
          actions: {
            handleHello,
            handleHowAreYou,
            handleName,
            handleAAU,
            handleCourseInput,
            handleFacultyInput,
            handleSimilarFaculty,
            handleCourseOption,
            handleSimilarCourses,
            handleError,
            handleMainOptions,
            handleGeneralOptions,
            handleContactOption,
            handleCourseRegistration,
            handlePaymentSteps,
            handleStudentGuide,
            handleUserGuide,
            handleThankYou,
          },
        });
      })}
    </div>
  );
};

export default ActionProvider;
