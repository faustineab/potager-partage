import { connect } from 'react-redux';

import Forum from 'src/components/Forum';
import { deleteCard } from '../store/reducer';


const mapStateToProps = state => ({
  questionList: state.questionList,
});

const mapDispatchToProps = dispatch => ({
  deleteCard: (cardId) => {
    dispatch(deleteCard(cardId));
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Forum);
