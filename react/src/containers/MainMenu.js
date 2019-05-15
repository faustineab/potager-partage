import { connect } from 'react-redux';
import MainMenu from '../components/MainMenu';
import { userLogout, fetchForumQuestions } from '../store/reducer';


const mapStateToProps = '';

const mapDispatchToProps = dispatch => ({
  onLogout: () => {
    dispatch(userLogout());
  },
  openForum: () => {
    dispatch(fetchForumQuestions());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(MainMenu);
