import { connect } from 'react-redux';

import Forum from 'src/components/Forum';


const mapStateToProps = state => ({
  questionList: state.questionList,
});

const mapDispatchToProps = {};


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Forum);
