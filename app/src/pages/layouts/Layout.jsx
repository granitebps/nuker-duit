import React from 'react';
import { Container, Row, Col, Card, Form, Button, Nav } from 'react-bootstrap';
import { Link, Outlet, useLocation, useNavigate } from 'react-router-dom';

const Layout = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const path = location.pathname;

  const handleLogout = () => {
    localStorage.removeItem('nuker-duit-token');
    navigate('/');
  };

  return (
    <Container fluid>
      <Row>
        <Col md={2} className='d-flex flex-nowrap'>
          <div className='d-flex flex-column flex-shrink-0 p-3 mt-2'>
            <ul className='nav nav-pills flex-column mb-auto'>
              <li className='nav-item'>
                <Link to={'home'} className={`nav-link text-black ${path == '/home' && 'active text-white'}`}>
                  Home
                </Link>
              </li>
              <li>
                <Link to={'buy'} className={`nav-link text-black ${path == '/buy' && 'active text-white'}`}>
                  Buy Transaction
                </Link>
              </li>
              <li>
                <Link to={'sell'} className={`nav-link text-black ${path == '/sell' && 'active text-white'}`}>
                  Sell Transaction
                </Link>
              </li>
              <li>
                <Link to={'summary'} className={`nav-link text-black ${path == '/summary' && 'active text-white'}`}>
                  Summary
                </Link>
              </li>
              <li>
                <a href='#' className='nav-link text-black' onClick={handleLogout}>
                  Logout
                </a>
              </li>
            </ul>
          </div>
        </Col>
        <Col md={10} className='py-4'>
          <Outlet />
        </Col>
      </Row>
    </Container>
  );
};

export default Layout;
