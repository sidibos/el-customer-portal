import { useEffect, useState } from "react";
import axios from "axios";
import { Alert, Button, Card, Form } from "react-bootstrap";
import AppLayout from "../../Components/AppLayout";
import Loading from "../../Components/Loading";

export default function Contact() {
  const [loaded, setLoaded] = useState(false);
  const [email, setEmail] = useState("");
  const [phone, setPhone] = useState("");
  const [saving, setSaving] = useState(false);
  const [msg, setMsg] = useState(null);
  const [errors, setErrors] = useState({});
  const [selectedSiteId, setSelectedSiteId] = useState(null);

  useEffect(() => {
    let cancelled = false;
    axios.get("/api/contact-details").then((res) => {
      if (cancelled) return;
      setEmail(res.data.email || "");
      setPhone(res.data.phone || "");
      setLoaded(true);
    });
    return () => { cancelled = true; };
  }, []);

  function validate() {
    const e = {};
    if (!email || !/^\S+@\S+\.\S+$/.test(email)) e.email = "Enter a valid email";
    if (!phone || phone.length < 7) e.phone = "Phone must be at least 7 characters";
    setErrors(e);
    return Object.keys(e).length === 0;
  }

  async function save() {
    setMsg(null);
    if (!validate()) return;

    setSaving(true);
    try {
      await axios.put("/api/contact-details", { email, phone });
      setMsg({ type: "success", text: "Contact details updated" });
    } catch (e) {
      if (e?.response?.status === 422) {
        const ve = e.response.data.errors || {};
        setErrors({
          email: ve.email?.[0],
          phone: ve.phone?.[0],
        });
      } else {
        setMsg({ type: "danger", text: e?.response?.data?.message || "Update failed" });
      }
    } finally {
      setSaving(false);
    }
  }

  if (!loaded) return <Loading />;

  return (
    <AppLayout selectedSiteId={selectedSiteId} onSelectSite={setSelectedSiteId}>
      <h4 className="mb-3">Contact details</h4>

      {msg && <Alert variant={msg.type}>{msg.text}</Alert>}

      <Card>
        <Card.Body>
          <Form.Group className="mb-3">
            <Form.Label>Email</Form.Label>
            <Form.Control value={email} onChange={(e) => setEmail(e.target.value)} isInvalid={!!errors.email} />
            <Form.Control.Feedback type="invalid">{errors.email}</Form.Control.Feedback>
          </Form.Group>

          <Form.Group className="mb-3">
            <Form.Label>Phone</Form.Label>
            <Form.Control value={phone} onChange={(e) => setPhone(e.target.value)} isInvalid={!!errors.phone} />
            <Form.Control.Feedback type="invalid">{errors.phone}</Form.Control.Feedback>
          </Form.Group>

          <Button onClick={save} disabled={saving}>
            {saving ? "Saving..." : "Save"}
          </Button>
        </Card.Body>
      </Card>
    </AppLayout>
  );
}