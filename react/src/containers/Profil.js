import { connect } from 'react-redux';
import Profile from '../components/Profil';
import {} from '../store/reducer';

const mapStateToProps = state => ({
  firstName: state.firstName,
  lastName: state.lastName,
  address1: state.address1,
  email: state.email,
  phoneNumber: state.phoneNumber,
});

const mapDispatchToProps = dispatch => ({
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Profile);
