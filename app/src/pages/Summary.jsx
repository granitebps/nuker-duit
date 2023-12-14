import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { Card, Spinner, Table, Form } from 'react-bootstrap';
import toast from 'react-hot-toast';
import { useNavigate } from 'react-router-dom';

const Summary = () => {
  const navigate = useNavigate();
  const [summaries, setSummaries] = useState([]);
  const [isLoading, setIsLoading] = useState(false);
  const [range, setRange] = useState('today');

  const fetch = async () => {
    setIsLoading(true);
    try {
      const { data } = await axios.get(`api/transactions/summary?range=${range}`, {
        headers: {
          Authorization: `Bearer ${localStorage.getItem('nuker-duit-token')}`,
        },
      });
      setSummaries(data.data);
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

  useEffect(() => {
    fetch();
  }, [range]);

  const handleRange = async (e) => {
    setRange(e.target.value);
  };

  return (
    <Card>
      <Card.Header>Currencies</Card.Header>
      <Card.Body>
        <Form.Select className='mb-3' onChange={handleRange}>
          <option value='today'>Today</option>
          <option value='week'>1 Week</option>
          <option value='month'>1 Month</option>
        </Form.Select>

        {isLoading ? (
          <div className='d-flex justify-content-center'>
            <Spinner />
          </div>
        ) : (
          <Table striped hover>
            <thead>
              <tr>
                <th>Currency</th>
                <th>Total Buy</th>
                <th>Total Sell</th>
                <th>Available Amount</th>
              </tr>
            </thead>
            <tbody>
              {summaries.map((i) => (
                <tr key={i.currency_name}>
                  <td>{i.currency_name.toUpperCase()}</td>
                  <td>{i.total_buy.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')}</td>
                  <td>{i.total_sell.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')}</td>
                  <td>{i.net_total.replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ',')}</td>
                </tr>
              ))}
            </tbody>
          </Table>
        )}
      </Card.Body>
    </Card>
  );
};

export default Summary;
