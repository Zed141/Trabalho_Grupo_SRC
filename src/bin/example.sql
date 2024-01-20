-- Chaves RSA geradas online, codificadas em base64, em https://www.devglan.com/online-tools/rsa-encryption-decryption
INSERT INTO users(id, email, name, active, key)
VALUES (1, 'a.upton@cipheredlock.moc', 'Alena Upton', 1, 'dummy'),
       (2, 'j.miller@cipheredlock.moc', 'Jerod Miller', 1, 'dummy'),
       (3, 'v.bradtke@cipheredlock.moc', 'Virgie Bradtke', 1, 'dummy'),
       (4, 'j.collier@cipheredlock.moc', 'Justen Collier', 1, 'dummy'),
       (5, 'm.aufderhar@cipheredlock.moc', 'Mckayla Aufderhar', 1, 'dummy'),
       (6, 'slopes@cipheredlock.moc', 'Sérgio Lopes', 1,
        'MIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAuLupstRTwG/UTuil9ukY\nXjVgD5DFH7scm2y7UVWBZDVlgYeSo7WetgSdgD9a85/GZMVkW6+qp7FgnqJQyCfj\nhkqCOQ/UqfAMIs0URjtq4bMKufFgemPE/c5UKbu/7MkZevbeJ7FPtcVWKdZ7Op8n\nukonh0iZg081ea0pQHmDjGS9afJTK3qojIAXDkqcaWM4AKw43kKe+N8pgPzsC4Nb\nZlnuHXhPOgYxrNqyDVSHRFLUWKm/Br0o4ccVIy2davOUNJxPQ7imYzf+fN2pkkDn\nshZjPrUM6RuJLGetpEISPDF+RoG17YLVCdo221ZMK/SMf1LcYcuItSbh2giah0cZ\ntiqUL26gFN67UwPb2r+hHQVLc5fXUy6Zgcn4IMY7hcF2A4aZ10wW8e5M/kwRCdza\ne101eh84OrgPZbzd+QRti8/5BSF/z8FxNS9FtmLPA+1bocUkPYc2rHEB97OuJh7e\nplGdHvwmBpwL8EVALd2842/Dd6WDnoD5QV9D1wFdVmLZEZqoRLeF4F8xpUSXsMp2\n3XGXh9F00ycKhKhad1jbDRphEeyn5hLL2u8RhaP2I3xEBhUZkm6AzVEjuvZ/oByP\nute5+yA+5d+RIPJ86gxUWwcaEsgbcMFvAaNbhNa6mGZ27XPN01UZHCjE5nz+XFqi\nO7RQcPc/TvDwPc3a1cc7pR8CAwEAAQ==');

INSERT INTO vaults(id, description, owner_id, data, url, notes, username)
VALUES (1, 'Vault 1', 1, 'dummy', NULL, NULL, ''),
       (2, 'Vault 2', 1, 'dummy', NULL, NULL, ''),
       (3, 'Vault 3', 1, 'dummy', NULL, NULL, ''),
       (4, 'Vault 4', 1, 'dummy', NULL, NULL, ''),
       (5, 'Vault 5', 1, 'dummy', NULL, NULL, ''),
       --
       (6, 'Vault 6', 3, 'dummy', NULL, NULL, ''),
       (7, 'Vault 7', 3, 'dummy', NULL, NULL, ''),
       (8, 'Vault 8', 3, 'dummy', NULL, NULL, ''),
       (9, 'Vault 9', 3, 'dummy', NULL, NULL, ''),
       (10, 'Vault 10', 3, 'dummy', NULL, NULL, ''),
       (11, 'Vault 11', 3, 'dummy', NULL, NULL, ''),
       (12, 'Vault 12', 3, 'dummy', NULL, NULL, ''),
       (13, 'Vault 13', 3, 'dummy', NULL, NULL, ''),
       --
       (14, 'Vault 14', 4, 'dummy', NULL, NULL, ''),
       (15, 'Vault 15', 4, 'dummy', NULL, NULL, ''),
       (16, 'Vault 16', 4, 'dummy', NULL, NULL, ''),
       (17, 'Vault 17', 4, 'dummy', NULL, NULL, ''),
       (18, 'Vault 18', 4, 'dummy', NULL, NULL, '');

INSERT INTO vault_access(user_id, vault_id, secret, nonce)
VALUES (1, 1, 'dummy', NULL),
       (1, 2, 'dummy', NULL),
       (1, 3, 'dummy', NULL),
       (1, 4, 'dummy', NULL),
       (1, 5, 'dummy', NULL),
       --
       (3, 6, 'dummy', NULL),
       (3, 7, 'dummy', NULL),
       (3, 8, 'dummy', NULL),
       (3, 9, 'dummy', NULL),
       (3, 10, 'dummy', NULL),
       (3, 11, 'dummy', NULL),
       (3, 12, 'dummy', NULL),
       (3, 13, 'dummy', NULL),
       --
       (4, 14, 'dummy', NULL),
       (4, 15, 'dummy', NULL),
       (4, 16, 'dummy', NULL),
       (4, 17, 'dummy', NULL),
       (4, 18, 'dummy', NULL),
       -- SHARED
       (2, 2, 'dummy', NULL),
       (2, 3, 'dummy', NULL),
       (2, 5, 'dummy', NULL),
       (5, 14, 'dummy', NULL);