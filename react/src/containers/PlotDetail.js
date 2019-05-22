import { connect } from 'react-redux';


import PlotDetail from 'src/components/Potager/PlotDetail';

import { bookPlot, inputChange, newVegetable, removeVegetable } from '../store/reducer';


const mapStateToProps = state => ({
  plotStatus: state.plotData.status,
  isUserPlot: state.isUserPlot,
  vegetablesList: state.vegetablesList,
  plotData: state.plotData,
  plotId: state.openPlotId,
  newVegetable: state.newVegetable,
});

const mapDispatchToProps = dispatch => ({
  bookPlot: () => {
    dispatch(bookPlot());
  },
  inputChange: (name, value) => {
    dispatch(inputChange(name, value));
  },
  submitVegetable: (newVegetableId) => {
    dispatch(newVegetable(newVegetableId));
  },
  removeVegetable: (toRemove) => {
    dispatch(removeVegetable(toRemove));
  }
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PlotDetail);
