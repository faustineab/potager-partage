import { connect } from 'react-redux';
import Potager from '../components/Potager';


const mapStateToProps = state => ({
  plots: state.gardenPlots,
  length: state.gardenNbPlotsRow,
  width: state.gardenNbPlotsColumn,
});

const mapDispatchToProps = {};

export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(Potager);
