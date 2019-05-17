import { connect } from 'react-redux';


import AskQuestion from 'src/components/Forum/AskQuestion';

import { inputChange, userAskingQuestion, submitQuestion, addTagToQuestion, removeQuestionTag } from '../store/reducer';


const mapStateToProps = state => ({
  question: state.questionToAsk,
  tags: state.tags,
  askingQuestion: state.askingQuestion,
  questionTags: state.questionTags,
  questionTitle: state.questionTitle,
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
  onQuestionSubmit: (title, question, tag) => {
    dispatch(submitQuestion(title, question, tag));
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(AskQuestion);

