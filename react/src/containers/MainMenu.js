import { connect } from 'react-redux';
import MainMenu from '../components/MainMenu';
import { userLogout } from '../store/reducer';


const mapStateToProps = '';

const mapDispatchToProps = dispatch => ({
  onLogout: () => {
    dispatch(userLogout());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(MainMenu);
