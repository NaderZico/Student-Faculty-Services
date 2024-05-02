import React from 'react';
import Guide from './User Guide.pdf';

const UserGuide = () => {
  return (
    <div>
      Click <a href={Guide} target="_blank" rel="noreferrer">here</a> for the full user guide on 
      how to use the Student-Faculty Services website.
    </div>
  );
};

export default UserGuide;
