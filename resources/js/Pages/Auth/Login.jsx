import { useState } from "react";
import { router } from "@inertiajs/react";
import axios from "axios";
import { Alert, Button, Card, Container, Form } from "react-bootstrap";

export default function Login() {
  const [email, setEmail] = useState("primary@example.com");
  const [password, setPassword] = useState("password");
  const [busy, setBusy] = useState(false);
  const [error, setError] = useState(null);

  async function submit(e) {
    e.preventDefault();
    setBusy(true);
    setError(null);

    try {
      await axios.post("/portal/login", { email, password }, { baseURL: "/" });
      router.visit("/dashboard");
    } catch (e) {
      setError(e?.response?.data?.message || "Login failed");
    } finally {
      setBusy(false);
    }
  }

  return (
    <Container className="py-5" style={{ maxWidth: 520 }}>
      <Card>
        <Card.Body>
          <Card.Title>Login</Card.Title>
          {error && <Alert variant="danger">{error}</Alert>}

          <Form onSubmit={submit}>
            <Form.Group className="mb-3">
              <Form.Label>Email</Form.Label>
              <Form.Control value={email} onChange={(e) => setEmail(e.target.value)} />
            </Form.Group>

            <Form.Group className="mb-3">
              <Form.Label>Password</Form.Label>
              <Form.Control type="password" value={password} onChange={(e) => setPassword(e.target.value)} />
            </Form.Group>

            <Button type="submit" disabled={busy} className="w-100">
              {busy ? "Signing in..." : "Sign in"}
            </Button>
          </Form>
        </Card.Body>
      </Card>
    </Container>
  );
}