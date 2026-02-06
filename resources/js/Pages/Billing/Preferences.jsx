import { useEffect, useState } from "react";
import axios from "axios";
import { Alert, Button, Card, Form } from "react-bootstrap";
import AppLayout from "../../Components/AppLayout";
import Loading from "../../Components/Loading";

export default function BillingPreferences() {
  const [format, setFormat] = useState(null);
  const [saving, setSaving] = useState(false);
  const [msg, setMsg] = useState(null);
  const [selectedSiteId, setSelectedSiteId] = useState(null);

  useEffect(() => {
    let cancelled = false;
    axios.get("/api/billing-preferences").then((res) => {
      if (cancelled) return;
      setFormat(res.data.format);
    });
    return () => { cancelled = true; };
  }, []);

  async function save() {
    setSaving(true);
    setMsg(null);
    try {
      const res = await axios.put("/api/billing-preferences", { format });
      setMsg({ type: "success", text: `Saved: ${res.data.format}` });
    } catch (e) {
      if (e?.response?.status === 422) {
        setMsg({ type: "danger", text: "Invalid billing format" });
      } else {
        setMsg({ type: "danger", text: e?.response?.data?.message || "Save failed" });
      }
    } finally {
      setSaving(false);
    }
  }

  if (!format) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <h4 className="mb-3">Billing preferences</h4>

      {msg && <Alert variant={msg.type}>{msg.text}</Alert>}

      <Card>
        <Card.Body>
          <Form.Group className="mb-3">
            <Form.Label>Billing format</Form.Label>
            <Form.Select value={format} onChange={(e) => setFormat(e.target.value)}>
              <option value="PDF">PDF</option>
              <option value="CSV">CSV</option>
              <option value="EDI">EDI</option>
            </Form.Select>
          </Form.Group>

          <Button onClick={save} disabled={saving}>
            {saving ? "Saving..." : "Save"}
          </Button>
        </Card.Body>
      </Card>
    </AppLayout>
  );
}