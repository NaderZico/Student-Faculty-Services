import Fuse from 'fuse.js';
import courseData from './Components/Course/course.json';

const courses = courseData.find((item) => item.type === 'table' && item.name === 'course');
const courseNames = courses.data.map((course) => course.course_name.toLowerCase());

const courseFuseOptions = {
  keys: courseNames,
  threshold: 0.35,
  includeScore: true,
  shouldSort: true
};

const courseFuse = new Fuse(courseNames, courseFuseOptions);

const parseCourse = (message, actions, setSelectedCourse) => {
  const lowerCase = message.toLowerCase().trim();

  if (lowerCase.length <= 3) {
    return false;
}

  const sixDigitsOnly = /^\d{6}$/;
  if (sixDigitsOnly.test(lowerCase)) {

    const courseByID = courses.data.find(course => course.course_id === lowerCase);
    if (courseByID) {
      setSelectedCourse(courseByID);
      actions.handleCourseInput(courseByID);
      return true; 
    }
  }

  const courseResults = courseFuse.search(lowerCase);
  console.log(courseResults);
  // Check if there are any matching courses
  if (courseResults.length > 0) {
    // Extract the top matching courses and their scores
    const similarCourses = courseResults.slice(0, 5).map(result => ({
      course: result.item,
      score: result.score
    }));

    // Check if the top matching course score is exactly 0 (exact match)
    if (similarCourses[0].score === 0) {
      const selectedCourse = courses.data.find(course => course.course_name.toLowerCase() === similarCourses[0].course);
      setSelectedCourse(selectedCourse);
      actions.handleCourseInput(selectedCourse);
      return true;
      
    } else if (similarCourses[0].score < 0.35) {
      // Pass the top courses to the action provider unless the score is exactly 0
      actions.handleSimilarCourses(similarCourses);
      return true;
    }

    return false;
  }

  return false;
};

export default parseCourse;
