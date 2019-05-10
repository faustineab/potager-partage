import { connect } from 'react-redux';


import AskQuestion from 'src/components/Forum/AskQuestion';

import { inputChange, userAskingQuestion, questionAsked, addTagToQuestion, removeQuestionTag } from '../store/reducer';


const mapStateToProps = state => ({
  question: state.questionToAsk,
  tags: state.tags,
  askingQuestion: state.askingQuestion,
  questionTags: state.questionTags,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  addTag: (tag) => {
    dispatch(addTagToQuestion(tag));
  },
  removeQuestionTag: (tagList) => {
    dispatch(removeQuestionTag(tagList));
  },
  userAskingQuestion: () => {
    dispatch(userAskingQuestion());
  },
  onQuestionSubmit: (question, tag) => {
    dispatch(questionAsked(question, tag));
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AskQuestion);
