import { connect } from 'react-redux';

import JoinGarden from 'src/components/Subscribe/joinGarden';

const mapStateToProps = state => ({
  gardenList: state.gardenList,
});

const mapDispatchToProps = {};


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(JoinGarden);
