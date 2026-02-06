import { useEffect, useState } from "react";
import axios from "axios";
import { Card, Col, Form, Row } from "react-bootstrap";
import AppLayout from "../Components/AppLayout";
import Loading from "../Components/Loading";
import ConsumptionBars from "../Components/ConsumptionBars";

export default function Dashboard() {
  const [dash, setDash] = useState(null);
  const [selectedSiteId, setSelectedSiteId] = useState(null);
  const [siteConsumption, setSiteConsumption] = useState(null);


  useEffect(() => {
    let cancelled = false;

    async function load() {
      // ✅ Calls API endpoints
      await axios.get('/sanctum/csrf-cookie');
      const dashRes = await axios.get("/api/dashboard"); // /api/dashboard
      const sitesRes = await axios.get("/api/sites");     // /api/sites

      if (cancelled) return;

      setDash(dashRes.data);

      const initialSiteId = sitesRes.data?.[0]?.id ?? null;
      setSelectedSiteId(initialSiteId);

      if (initialSiteId) {
        const consRes = await axios.get(`/api/sites/${initialSiteId}/consumption?months=6`);
        if (!cancelled) setSiteConsumption(consRes.data);
      }
    }

    load();
    return () => { cancelled = true; };
  }, []);

  useEffect(() => {
    let cancelled = false;

    async function loadConsumption() {
      if (!selectedSiteId) return;
      const consRes = await axios.get(`/api/sites/${selectedSiteId}/consumption?months=6`);
      if (!cancelled) setSiteConsumption(consRes.data);
    }

    loadConsumption();
    return () => { cancelled = true; };
  }, [selectedSiteId]);

  if (!dash) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <div className="d-flex justify-content-between align-items-center mb-3">
        <h4 className="mb-0">{dash.customerName}</h4>
        <Form.Text className="text-muted">
          Data is scoped to your customer
        </Form.Text>
      </div>

      <Row className="g-3">
        <Col xs={12} md={4}>
          <Card><Card.Body>
            <div className="text-muted">Sites</div>
            <div className="fs-3">{dash.sitesCount}</div>
          </Card.Body></Card>
        </Col>

        <Col xs={12} md={4}>
          <Card><Card.Body>
            <div className="text-muted">Active meters</div>
            <div className="fs-3">{dash.activeMetersCount}</div>
          </Card.Body></Card>
        </Col>

        <Col xs={12} md={4}>
          <Card><Card.Body>
            <div className="text-muted">Outstanding</div>
            <div className="fs-3">£{Number(dash.outstandingBalance).toFixed(2)}</div>
            <div className="text-muted small">
              Last bill: £{Number(dash.lastBillAmount).toFixed(2)}
            </div>
          </Card.Body></Card>
        </Col>
      </Row>

      <ConsumptionBars
        title="Selected site consumption (last 6 months)"
        data={siteConsumption?.data || []}
      />
    </AppLayout>
  );
}