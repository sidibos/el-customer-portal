import { useEffect, useState } from "react";
import { Link, router } from "@inertiajs/react";
import axios from "axios";
import { Container, Nav, Navbar, NavDropdown, Form } from "react-bootstrap";

export default function AppLayout({ children, selectedSiteId, onSelectSite }) {
  const [sites, setSites] = useState([]);
  const [ctx, setCtx] = useState(null);

  useEffect(() => {
    let cancelled = false;

    async function load() {
      const [userRes, sitesRes] = await Promise.all([
        axios.get("/api/user"),
        axios.get("/api/sites"),
      ]);

      if (cancelled) return;

      setCtx(userRes.data);
      setSites(sitesRes.data);

      if (!selectedSiteId && sitesRes.data?.length) {
        onSelectSite?.(sitesRes.data[0].id);
      }
    }

    load();
    return () => {
      cancelled = true;
    };
  }, [selectedSiteId, onSelectSite]);

  async function logout() {
    await axios.post("/portal/logout");
    router.visit("/login");
  }

  return (
    <>
      <Navbar bg="dark" variant="dark" expand="lg" sticky="top">
        <Container>
          <Navbar.Brand as={Link} href="/dashboard">
            Customer Portal
          </Navbar.Brand>
          <Navbar.Toggle />
          <Navbar.Collapse>
            <Nav className="me-auto">
              <Nav.Link as={Link} href="/dashboard">Dashboard</Nav.Link>
              <Nav.Link as={Link} href="/sites">Sites</Nav.Link>
              <Nav.Link as={Link} href="/billing">Billing</Nav.Link>
              <Nav.Link as={Link} href="/contact">Contact</Nav.Link>
            </Nav>

            {sites.length > 0 && (
              <Form className="me-3">
                <Form.Select
                  size="sm"
                  value={selectedSiteId || ""}
                  onChange={(e) => onSelectSite?.(Number(e.target.value))}
                >
                  {sites.map((s) => (
                    <option key={s.id} value={s.id}>
                      {s.name}
                    </option>
                  ))}
                </Form.Select>
              </Form>
            )}

            <Nav>
              <NavDropdown title={ctx?.user?.email || "Account"} align="end">
                <NavDropdown.Item disabled>
                  Type: {ctx?.user?.type || "-"}
                </NavDropdown.Item>
                <NavDropdown.Divider />
                <NavDropdown.Item onClick={logout}>Logout</NavDropdown.Item>
              </NavDropdown>
            </Nav>
          </Navbar.Collapse>
        </Container>
      </Navbar>

      <Container className="py-4">{children}</Container>
    </>
  );
}