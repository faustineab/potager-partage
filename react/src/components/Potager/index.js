import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Button } from 'semantic-ui-react';

import PlotDetail from 'src/containers/PlotDetail';


import './index.scss';


class Potager extends Component {
  handleClick = (evt) => {
    const { openPlot } = this.props;

    const plotId = evt.currentTarget.id;
    openPlot(plotId);
  }

  createTable = () => {
    // Je récupère mes props
    // Puis je crée un variable table, qui est un tableau vide
    const { length, width, plots } = this.props;
    const table = [];

    // Pour afficher le jardin, je crée une boucle for,
    // La boucle commence à i=0 et se termine quand i<width
    for (let i = 0; i < width; i++) {
      // Puis je crée un variable children, qui est un tableau vide
      const children = [];
      // Puis je crée une const rowPLots qui contient les Plots dont j'ai besoin
      // Pour i=0, je ramène les plots d'index 0 à 3
      // Pour i=1, je ramène les plots d'index 4 à 7
      // Pour i=2, je ramène les plots d'index 8 à 11
      // la borne basse sera égale à (i * length), la borne haute à (length * (i + 1))
      const rowPlots = plots.filter((plot, index) => index >= (length * i) && index < (length * (i + 1)));
      console.log(rowPlots);
      // Je map sur mon nouveau tableau, pour créer une div pour chaque plot
      const row = rowPlots.map((plot, index) => <div key={`row${i}plot${index}`} className={`table-cell ${plot.status}`} id={plot.id} onClick={this.handleClick} />);
      // Je push ma variable row dans children
      children.push(row);
      table.push(<div key={i} className="gardenRow">{children}</div>);
    }

    return table;
  }


  render() {
    const { plotOpened, gardenName } = this.props;
    return (
      <main id="garden">
        <div id="garden-container">
          <h1 id="garden-title">{gardenName}</h1>
          <div id="garden-body">
            <div className="table-container">
              {this.createTable()}
            </div>
            {plotOpened && (
              <aside className="todo">
                <PlotDetail />
              </aside>
            )}
          </div>
        </div>
      </main>
    );
  }
}

Potager.propTypes = {
  openPlot: PropTypes.func.isRequired,
  gardenName: PropTypes.string.isRequired,
  plotOpened: PropTypes.bool.isRequired,
  length: PropTypes.number.isRequired,
  width: PropTypes.number.isRequired,
  plots: PropTypes.arrayOf(PropTypes.shape({
    id: PropTypes.number,
    status: PropTypes.string,
  })).isRequired,
};

export default Potager;
