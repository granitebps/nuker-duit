import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { Row, Col, Card, Spinner } from 'react-bootstrap';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

const Home = () => {
  const navigate = useNavigate();
  const [currencies, setCurrencies] = useState([]);
  const [isLoading, setIsLoading] = useState(false);

  useEffect(() => {
    const fetch = async () => {
      setIsLoading(true);
      try {
        const { data } = await axios.get('api/currencies', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('nuker-duit-token')}`,
          },
        });
        setCurrencies(data.data);
      } catch (error) {
        if (axios.isAxiosError(error)) {
          if (error.response.status) {
            navigate('/');
            localStorage.removeItem('nuker-duit-token');
            return;
          }
          toast.error(error.response.data.message);
        } else {
          toast.error(error.message);
        }
        console.log(error);
      }
      setIsLoading(false);
    };
    fetch();
  }, []);

  return (
    <Card>
      <Card.Header>Currencies</Card.Header>
      <Card.Body>
        <Row className='justify-content-center'>
          {isLoading ? (
            <Spinner />
          ) : (
            currencies.map((i) => (
              <Col key={i.id} md={3}>
                <Card>
                  <Card.Header>{i.currency.toUpperCase()}</Card.Header>
                  <Card.Body>
                    <h2 className='text-center'>{i.rate.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')}</h2>
                  </Card.Body>
                </Card>
              </Col>
            ))
          )}
        </Row>
      </Card.Body>
    </Card>
  );
};

export default Home;
