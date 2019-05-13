import React, { Component } from 'react';
import { Button } from 'semantic-ui-react';
import { Link } from 'react-router-dom';

import './index.scss';

class Potager extends React.Component {
  createTable = () => {
    const table = [];

    for (let i = 0; i < 4; i++) {
      const children = [];
      for (let j = 0; j < 4; j++) {
        children.push(<td key={`row${j}column${i}`} className="table-cell">{`Column ${j + 1}`}</td>);
      }
      table.push(<tbody><tr key={i}>{children}</tr></tbody>);
    }
    return table;
  }


  render() {
    return (
      <div>
        <div className="body">
          <table className="table-container">
            {this.createTable()}
          </table>
          <div className="todo">
            <h1>Todo list ici</h1>
          </div>
        </div>
        <div className="utils">
          <Button className="button">Absence</Button>
          <Button className="button">Événement</Button>
        </div>
      </div>
    );
  }
}
export default Potager;

/* <div>
<div className="body">
  <div className="grid-container">
    <div className="grid-item"><h1>Tomate</h1></div>
    <div className="grid-item"><h1>Abricot</h1></div>
    <div className="grid-item" />
    <div className="grid-item" />
    <div className="grid-item" />
    <div className="grid-item"><h1>Persil</h1></div>
    <div className="grid-item"><h1>Tomate</h1></div>
    <div className="grid-item" />
    <div className="grid-item" />
    <div className="grid-item"><h1>Pomme</h1></div>
    <div className="grid-item" />
    <div className="grid-item"><h1>Carotte</h1></div>
    <div className="grid-item" />
    <div className="grid-item"><h1>Poire</h1></div>
    <div className="grid-item" />
  </div>
  <div className="todo">
    <h1>Todo list ici</h1>
  </div>
</div>
<div className="utils">
  <Button className="button">Absence</Button>
  <Button className="button">Événement</Button>
</div>
</div> */
