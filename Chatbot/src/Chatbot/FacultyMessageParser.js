import Fuse from 'fuse.js';
import facultyData from './Components/Faculty/faculty.json';

// Find the faculty data
const faculty = facultyData.find((item) => item.type === 'table' && item.name === 'a');

// Extract the faculty names from the faculty data
const facultyNames = faculty.data.map((faculty) => faculty.faculty_name.toLowerCase());

// Configure Fuse.js options for faculty names
const facultyFuseOptions = {
  keys: ['faculty_name'],
  threshold: 0.3, 
  includeScore: true,
  shouldSort: true
};

const facultyFuse = new Fuse(facultyNames, facultyFuseOptions);

const parseFaculty = (message, actions, setSelectedFaculty) => {
  const lowerCase = message.toLowerCase().trim();

  if (lowerCase.length <= 3) {
    return false; // Message is too vague, return false to trigger handleError()
}

  // Fuzzy search for faculty names
  const facultyResults = facultyFuse.search(lowerCase);
  console.log(facultyResults);
  // Check if there are any matching faculty names
  if (facultyResults.length > 0) {
    // Extract the top three matching faculty and their scores
    const similarFaculty = facultyResults.slice(0, 5).map(result => ({
      faculty: result.item,
      score: result.score
    }));

    // Check if the top matching faculty score is exactly 0 (exact match)
    if (similarFaculty[0].score === 0) {
      // Pass the exact match faculty to the action provider
      const selectedFaculty = faculty.data.find(faculty => faculty.faculty_name.toLowerCase() === similarFaculty[0].faculty);
      setSelectedFaculty(selectedFaculty);
      actions.handleFacultyInput(selectedFaculty);
      return true;

    } else if (similarFaculty[0].score < 0.3) {
      // Pass the top three faculty to the action provider unless the score is exactly 0
      actions.handleSimilarFaculty(similarFaculty);
      return true;
    }

    return false;
  }

  return false;
};

export default parseFaculty;

