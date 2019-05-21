import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Card, Icon, Button, Comment, Form, Header } from 'semantic-ui-react';

import './index.scss';

class PostDetail extends Component {
  componentWillMount() {
    const { fetchQuestionDetail } = this.props;
    fetchQuestionDetail();
  }

  handleInputChange =(evt) => {
    const { inputChange } = this.props;
    const { name, value } = evt.currentTarget;
    console.log(name);
    console.log(value);
    inputChange(name, value);
  };

  handleSubmit = (evt) => {
    evt.preventDefault();
    console.log('submit');
    const { sendAnswer } = this.props;
    sendAnswer();
  }

  handleDeleteCard = (evt) => {
    const { deleteCard } = this.props;
    const cardId = evt.currentTarget.id;
    console.log(cardId);
    deleteCard(cardId);
  };

  handleDeleteAnswer = (evt) => {
    const {deleteAnswer } = this.props;
    const answerId = evt.currentTarget.id;
    console.log(answerId);
    deleteAnswer(answerId);
  }

  render() {
    const { answers, questionDetail, ongoingAnswer } = this.props;
    const { createdAt, id, tags, text, title, user } = questionDetail;

    return (
      <main id="postDetail">
        <Card fluid className="forumCard">
          <Card.Header className="cardHeader">
            <div>
              <h3>
                {title}
              </h3>
            </div>
            <div id="headerMeta">
              <span className="postDetail">publié par {user.name} le {createdAt}</span>
              {tags.map(({ name }) => <span className="tag">{name}</span>)}
              <Icon name="ban" id={id} className="cardDelete" onClick={this.handleDeleteCard} />
            </div>
          </Card.Header>
          <Card.Content id="questionContent">
            <p>{text}</p>
          </Card.Content>
        </Card>

        <Comment.Group id="postComments">
          <Header as="h3" dividing>
            Réponses
          </Header>

          {answers.map(answer => (
            <Comment>
              <Comment.Content>
                <Comment.Content id="commentHeader">
                  <Comment.Author>{answer.user.name}</Comment.Author>
                  <Icon name="ban" id={answer.id} className="commentDelete" onClick={this.handleDeleteAnswer} />
                </Comment.Content>
                <Comment.Metadata>
                  <div>{answer.createdAt}</div>
                </Comment.Metadata>
                <Comment.Text>{answer.text}</Comment.Text>
              </Comment.Content>
            </Comment>
          ))}
        </Comment.Group>

        <Form id="submitComment" reply onSubmit={this.handleSubmit}>
          <Form.TextArea name="answer" value={ongoingAnswer} onChange={this.handleInputChange} />
          <Button type="submit" content="Répondre" floated="right" />
        </Form>
      </main>
    );
  }
}

export default PostDetail;
