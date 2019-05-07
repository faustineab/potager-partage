import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Form, Popup, Button } from 'semantic-ui-react';

import './index.scss';

class AskQuestion extends Component {
  handleSubmit = (evt) => {
    evt.preventDefault();
    const { onQuestionSubmit } = this.props;

    onQuestionSubmit();
  }

  handleClick =(evt) => {
    const { userAskingQuestion } = this.props;

    userAskingQuestion();
  }

  handleChange =(evt) => {
    const { inputChange } = this.props;
    const { name } = evt.target;
    const { value } = evt.target;
    console.log(name, value);
    inputChange(name, value);
  }

  render() {
    const { tags, question, askingQuestion } = this.props;

    console.log('askingQuestion', askingQuestion);
    console.log('question', question);
    console.log('tags', tags);

    return (
      <div id="newQuestion">
        <Popup
          content="Ajouter une question"
          style={{ FontFamily: 'Fresca, sans-serif', color: '#5e5250' }}
          trigger={<Button id="addQuestion" icon={askingQuestion ? 'minus' : 'add'} onClick={this.handleClick} />}
        />
        <Form id={askingQuestion ? 'showQuestionForm' : 'hideQuestionForm'} onSubmit={this.handleSubmit}>
          <Form.TextArea
            id="questionText"
            name="questionToAsk"
            value={question}
            placeholder="posez votre question ..."
            onChange={this.handleChange}
          />
          <Form.Group>
            <Form.Select
              id="selectTags"
              name="questionTag"
              onChange={this.handleChange}
              options={tags}
              placeholder="tag"
            />
            <Form.Button content="Poser" floated="right" />
          </Form.Group>
        </Form>
      </div>
    );
  }
}

AskQuestion.propTypes = {
  question: PropTypes.string,
  tags: PropTypes.arrayOf(PropTypes.object.isRequired).isRequired,
  askingQuestion: PropTypes.bool.isRequired,
  inputChange: PropTypes.func.isRequired,
  userAskingQuestion: PropTypes.func.isRequired,
  onQuestionSubmit: PropTypes.func.isRequired,
};

AskQuestion.defaultProps = {
  question: '',
};

export default AskQuestion;
