import React, { Component } from 'react';
import PropTypes from 'prop-types';
import { Card, Icon, Button, Comment, Form, Header } from 'semantic-ui-react';

import './index.scss';

class PostDetail extends Component {
  componentDidMount() {
    const { fetchQuestionDetail } = this.props;
    fetchQuestionDetail();
  }

  handleInputChange = (evt) => {
    const { inputChange } = this.props;
    const { name, value } = evt.currentTarget;
    console.log(name);
    console.log(value);
    inputChange(name, value);
  }

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
  }

  handleDeleteAnswer = (evt) => {
    const { deleteAnswer } = this.props;
    const answerId = evt.currentTarget.id;
    console.log(answerId);
    deleteAnswer(answerId);
  }

  render() {
    const { answers, questionDetail, ongoingAnswer, questionTags, author } = this.props;
    const { createdAt, id, text, title } = questionDetail;
    console.log('answers', answers);
    console.log('qDetail', questionDetail);
    console.log('qTags', questionTags);
    console.log('author', author);

    return (
      <main id="postDetail">
        <div id="post-header">
          <div id="post-container">
            <h2 id="post-title">Le coin des jardiniers</h2>
          </div>
        </div>
        <div id="post-body">
          <Card fluid className="forumCard">
            <Card.Header className="cardHeader">
              <div>
                <h3>
                  {title}
                </h3>
              </div>
              <div id="headerMeta">
                <span className="postDetail">publié par {author.name} le {createdAt}</span>
                {questionTags.map(({ name }) => <span className="tag">{name}</span>)}
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
        </div>

        
      </main>
    );
  }
}

PostDetail.propTypes = {
  fetchQuestionDetail: PropTypes.func.isRequired,
  inputChange: PropTypes.func.isRequired,
  sendAnswer: PropTypes.func.isRequired,
  deleteAnswer: PropTypes.func.isRequired,
  deleteCard: PropTypes.func.isRequired,
  ongoingAnswer: PropTypes.string,
  answers: PropTypes.array,
  author: PropTypes.objectOf(PropTypes.shape({
    id: PropTypes.number,
    name: PropTypes.string,
  })),
  questionTags: PropTypes.arrayOf(
    PropTypes.objectOf(
      PropTypes.shape({
        id: PropTypes.number,
        name: PropTypes.string,
      }),
    ),
  ),
  questionDetail: PropTypes.objectOf(PropTypes.shape({
    id: PropTypes.number,
    title: PropTypes.string,
    text: PropTypes.string,
    createdAt: PropTypes.string,
    answers: PropTypes.arrayOf(
      PropTypes.shape({
        createdAt: PropTypes.string,
        text: PropTypes.string,
        user: PropTypes.shape({
          id: PropTypes.number,
          name: PropTypes.string,
        }),
      }),
    ),
  })),
};

PostDetail.defaultProps = {
  ongoingAnswer: '',
  answers: [],
  questionDetail: {},
  author: {},
  questionTags: [],
};

export default PostDetail;
