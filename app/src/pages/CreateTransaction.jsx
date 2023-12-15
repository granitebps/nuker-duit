import axios from 'axios';
import React, { useEffect, useState } from 'react';
import { Card, Form, Button, Spinner } from 'react-bootstrap';
import { useForm } from 'react-hook-form';
import toast from 'react-hot-toast';
import { useNavigate, useParams } from 'react-router-dom';

const CreateTransaction = () => {
  const param = useParams();
  const navigate = useNavigate();
  const [currencies, setCurrencies] = useState([]);
  const [loading, setLoading] = useState(false);

  const {
    register,
    handleSubmit,
    watch,
    setValue,
    formState: { errors, isSubmitting },
  } = useForm();

  const watchCurrency = watch('currency');
  const watchAmount = watch('amount');

  useEffect(() => {
    if (watchCurrency) {
      const amount = watchAmount || 0;
      const cur = currencies.find((i) => i.id == watchCurrency);
      const total = parseFloat(cur.rate) * parseFloat(amount);
      setValue('total', total);
    }
  }, [watchCurrency, watchAmount]);

  useEffect(() => {
    const fetch = async () => {
      setLoading(true);
      try {
        const { data } = await axios.get('api/currencies', {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('nuker-duit-token')}`,
          },
        });
        setCurrencies(data.data);
      } catch (error) {
        if (axios.isAxiosError(error)) {
          if (error.response.status == 401) {
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
      setLoading(false);
    };
    fetch();
  }, []);

  const onSubmit = async (input) => {
    try {
      const cur = currencies.find((i) => i.id == input.currency);

      await axios.post(
        'api/transactions',
        {
          currency_id: input.currency,
          amount: input.amount,
          total: input.total,
          rate: cur.rate,
          side: param.side,
        },
        {
          headers: {
            Authorization: `Bearer ${localStorage.getItem('nuker-duit-token')}`,
          },
        }
      );
      toast.success('Create transaction success');
    } catch (error) {
      if (axios.isAxiosError(error)) {
        if (error.response.status == 401) {
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
  };

  return (
    <Card>
      <Card.Body>
        {loading ? (
          <div className='d-flex justify-content-center'>
            <Spinner />
          </div>
        ) : (
          <Form onSubmit={handleSubmit(onSubmit)}>
            <Form.Group className='mb-3'>
              <Form.Label>Currency</Form.Label>
              <Form.Select {...register('currency', { required: 'Required' })} isInvalid={errors.currency}>
                <option value=''>Select Currency</option>
                {currencies.map((i) => (
                  <option key={i.id} value={i.id}>
                    {i.currency.toUpperCase()}
                  </option>
                ))}
              </Form.Select>
              <Form.Control.Feedback type='invalid'>{errors?.currency?.message}</Form.Control.Feedback>
            </Form.Group>

            <Form.Group className='mb-3'>
              <Form.Label>Amount</Form.Label>
              <Form.Control
                type='number'
                placeholder='Amount'
                isInvalid={errors.amount}
                {...register('amount', { required: 'Required' })}
              />
              <Form.Control.Feedback type='invalid'>{errors?.amount?.message}</Form.Control.Feedback>
            </Form.Group>

            <Form.Group className='mb-3'>
              <Form.Label>Total in IDR</Form.Label>
              <Form.Control disabled {...register('total')} placeholder='Total' />
            </Form.Group>

            <Button variant='success' type='submit' disabled={isSubmitting}>
              Submit
            </Button>
          </Form>
        )}
      </Card.Body>
    </Card>
  );
};

export default CreateTransaction;
