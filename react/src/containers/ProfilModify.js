import { connect } from 'react-redux';
import ProfileModify from '../components/ProfilModify';
import { inputChange, ModifyUserInfos } from '../store/reducer';

const mapStateToProps = state => ({
  firstName: state.firstName,
  lastName: state.lastName,
  email: state.email,
  phoneNumber: state.phoneNumber,
  gardenAddress: state.gardenAddress,
  gardenAddressSpecificities: state.gardenAddressSpecificities,
  gardenZipcode: state.gardenZipcode,
});

const mapDispatchToProps = dispatch => ({
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  ModifyUserInfos: () => {
    dispatch(ModifyUserInfos());
  },
});

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(ProfileModify);
