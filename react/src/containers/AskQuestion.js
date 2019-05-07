import { connect } from 'react-redux';

import AskQuestion from 'src/components/Forum/AskQuestion';

import { inputChange, userAskingQuestion, questionAsked } from '../store/reducer';


const mapStateToProps = state => ({
  question: state.questionToAsk,
  tags: state.tags,
  askingQuestion: state.askingQuestion,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
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
