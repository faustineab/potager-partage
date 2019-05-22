import { connect } from 'react-redux';


import PlotDetail from 'src/components/Potager/PlotDetail';

import { bookPlot, inputChange, newVegetable, removeVegetable, vegetableToAdd, unlinkPlot } from '../store/reducer';


const mapStateToProps = state => ({
  plotStatus: state.plotData.status,
  isUserPlot: state.isUserPlot,
  vegetablesList: state.vegetablesList,
  plotData: state.plotData,
  plotId: state.openPlotId,
  newVegetable: state.newVegetable,
  addingVegetable: state.addingVegetable,
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
  },
  vegetableToAdd: (vegetableName) => {
    dispatch(vegetableToAdd(vegetableName));
  },
  unlinkPlot: () => {
    dispatch(unlinkPlot());
  },
});


export default connect(
  mapStateToProps,
  mapDispatchToProps,
)(PlotDetail);
