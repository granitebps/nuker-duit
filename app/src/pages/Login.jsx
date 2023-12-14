import axios from 'axios';
import React from 'react';
import { Container, Row, Col, Card, Form, Button } from 'react-bootstrap';
import { useForm } from 'react-hook-form';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const navigate = useNavigate();
  const {
    register,
    handleSubmit,
    formState: { errors },
  } = useForm();

  const onSubmit = async (payload) => {
    try {
      const { data } = await axios.post('api/login', {
        username: payload.username,
        password: payload.password,
      });

      const token = data.data.token;
      localStorage.setItem('nuker-duit-token', token);

      // navigate('/home');
    } catch (error) {
      if (axios.isAxiosError(error)) {
        toast.error(error.response.data.message);
      } else {
        toast.error(error.message);
      }
      console.log(error);
    }
  };

  return (
    <Container>
      <Row className='vh-100 justify-content-center align-items-center'>
        <Col md={8}>
          <Card>
            <Card.Header>NukerDuit</Card.Header>
            <Card.Body>
              <Form onSubmit={handleSubmit(onSubmit)}>
                <Form.Group className='mb-3'>
                  <Form.Label>Username</Form.Label>
                  <Form.Control
                    type='text'
                    placeholder='Username'
                    isInvalid={errors.username}
                    {...register('username', { required: 'Required' })}
                  />
                  <Form.Control.Feedback type='invalid'>{errors?.username?.message}</Form.Control.Feedback>
                </Form.Group>

                <Form.Group className='mb-3'>
                  <Form.Label>Passwors</Form.Label>
                  <Form.Control
                    type='password'
                    placeholder='Password'
                    isInvalid={errors.password}
                    {...register('password', { required: 'Required' })}
                  />
                  <Form.Control.Feedback type='invalid'>{errors?.password?.message}</Form.Control.Feedback>
                </Form.Group>

                <Button variant='success' type='submit'>
                  Login
                </Button>
              </Form>
            </Card.Body>
          </Card>
        </Col>
      </Row>
    </Container>
  );
};

export default Login;
