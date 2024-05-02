import React from 'react';
import Guide from './Web Banner Guide.pdf';

const StudentGuide = () => {
  return (
    <div>
      Click <a href={Guide} target="_blank" rel="noreferrer">here</a> for the full Student Self-Service guide,
      where you can find the steps to browse and register courses in the AAU.
    </div>
  );
};

export default StudentGuide;
