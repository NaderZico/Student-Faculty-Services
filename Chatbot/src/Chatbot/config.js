import { createChatBotMessage } from 'react-chatbot-kit';
import Options from "./Components/MainOptions/Options.jsx";
import AAU from "./Components/AAU/AAU.jsx";
import GeneralOptions from "./Components/GeneralOptions/GeneralOptions.jsx";
import CourseRegistration from "./Components/GeneralOptions/CourseRegistration/CourseRegistration.jsx";
import PaymentSteps from "./Components/GeneralOptions/PaymentSteps/PaymentSteps.jsx";

const botName = 'Farah';

const config = {
  initialMessages: [
    createChatBotMessage(`Hello! I'm ${botName}, here to assist you. Courtesy of Al Ain University.`),
    createChatBotMessage("Start by typing 'Help' or 'Options' to display the available services.")
  ],
  widgets: [
    {
      widgetName: "Options",
      widgetFunc: (props) => <Options {...props} />,
    },
    {
      widgetName: "AAU",
      widgetFunc: (props) => <AAU {...props} />,
    },
    {
      widgetName: "General",
      widgetFunc: (props) => <GeneralOptions {...props} />
    },
    {
      widgetName: "CourseRegistration",
      widgetFunc: (props) => <CourseRegistration {...props} />
    },
    {
      widgetName: "PaymentSteps",
      widgetFunc: (props) => <PaymentSteps {...props} />
    },
  ],
  botName: botName,
};

export default config;